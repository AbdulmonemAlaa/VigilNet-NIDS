from threading import Thread


import random


import queue





from scapy.all import *





import mysql.connector





from datetime import datetime





from RuleFileReader import *





from Rule import *





from PrintPacket import *





from PacketStrings import *





from send_email import send_email_alert





from collections import defaultdict, deque





import time





from concurrent.futures import ThreadPoolExecutor





class DBConnection:



    connection = None



    @staticmethod

    def get_connection():



        if (

            DBConnection.connection is None

            or not DBConnection.connection.is_connected()

        ):



            DBConnection.connection = mysql.connector.connect(

                host="192.168.1.10", user="abdou", passwd="abdou", database="ids"

            )



        return DBConnection.connection





host_ip = conf.route.route("0.0.0.0")[1]





alerted_ips = set()





def log_alert(alertMessage, pkt,id):



    if IP in pkt:



        source_ip, destination_ip = pkt[IP].src, pkt[IP].dst



        protocol_map = {1: "ICMP", 6: "TCP", 17: "UDP"}



        protocol = pkt[IP].proto



        protocol_name = protocol_map.get(protocol, "Unknown")



        timestamp = datetime.now()



        # Initialize default values




        source_port = random.randint(5000, 9999)
        destination_port = random.randint(5000, 9999)
                    
            



        ip_info = ipString(pkt[IP])



        transport_info, application_info = "", ""



        if TCP in pkt:



            source_port, destination_port = pkt[TCP].sport, pkt[TCP].dport



            transport_info = tcpString(pkt[TCP])



            application_info = "[TCP Payload]" + "\n" + payloadString(pkt)



        elif UDP in pkt:



            source_port, destination_port = pkt[UDP].sport, pkt[UDP].dport



            transport_info = udpString(pkt[UDP])



            application_info = "[UDP Payload]" + "\n" + payloadString(pkt)



        elif ICMP in pkt:



            # Assuming icmpString function exists and formats ICMP packet details



            transport_info = icmpString(pkt[ICMP])



            application_info = "[ICMP Payload]" + "\n" + payloadString(pkt)



        # Updated insert query



        insert_query = """INSERT INTO alert_logs 

        (timestamp, source_ip, source_port, destination_ip, destination_port, protocol, transport_info, ip_info, application_info,alert_info,packet_id) 

        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""



        db = DBConnection.get_connection()



        cursor = db.cursor()



        try:



            cursor.execute(

                insert_query,

                (

                    timestamp,

                    source_ip,

                    source_port,

                    destination_ip,

                    destination_port,

                    protocol_name,

                    transport_info,

                    ip_info,

                    application_info,

                    alertMessage,
                    id,  # Add the missing alertMessage parameter here

                ),

            )



            db.commit()



        except mysql.connector.Error as err:



            print(f"Failed to insert alert into MySQL table: {err}")



        finally:



            cursor.close()





def getMatchedMessage1(rule):



    """Return the message to be logged when the packet triggered the rule."""



    msg = " ALERT "



    msg += "Rule matched :\n" + rule + "\n"



    return msg





class EventCounter:

    def __init__(self, max_count, time_window):



        self.max_count = max_count



        self.time_window = time_window



        self.events = defaultdict(deque)



        self.alerted = defaultdict(bool)  # Track if alert has been sent



    def add_and_check_event(self, key, current_time=None):



        if not current_time:



            current_time = time.time()



        events_queue = self.events[key]



        events_queue.append(current_time)



        # Remove old events outside the time window



        while events_queue and current_time - events_queue[0] > self.time_window:



            events_queue.popleft()



        # Reset alert if no events in time window



        if not events_queue:



            self.alerted[key] = False



        # Check for attack pattern and whether an alert has been sent



        if len(events_queue) >= self.max_count and not self.alerted[key]:



            self.alerted[key] = True  # Mark as alerted



            return True



        return False





# Initialize EventCounters for various attacks





event_counters = {

    "syn_scan": EventCounter(max_count=25, time_window=5),

    "udp_scan": EventCounter(max_count=20, time_window=5),

    "icmp_sweep": EventCounter(max_count=100, time_window=20),

    "ssh_brute_force": EventCounter(max_count=30, time_window=20),

    "ftp_brute_force": EventCounter(max_count=40, time_window=20),

}





event_rule = {

    "syn_scan": "Action: Action.ALERT, Protocol: Protocol.TCP, Src IPs: IPv4Network('0.0.0.0/0'), Src Ports: any, Dst IPs: IPv4Network('0.0.0.0/0'), Dst Ports: any, Message: {'msg': 'SYN SCAN DETECT', 'tos': None, 'len': None, 'offset': None, 'seq': None, 'ack': None, 'flags': 'S', 'time': 5, 'count': 100}",

    "udp_scan": "Action: Action.ALERT, Protocol: Protocol.UDP, Src IPs: IPv4Network('0.0.0.0/0'), Src Ports: any, Dst IPs: IPv4Network('0.0.0.0/0'), Dst Ports: any, Message: {'msg': 'UDP SCAN DETECT', 'tos': None, 'len': None, 'offset': None, 'seq': None, 'ack': None, 'flags': 'S', 'time': 5, 'count': 100}",

    "icmp_sweep": "Action: Action.ALERT, Protocol: Protocol.ICMP, Src IPs: IPv4Network('0.0.0.0/0'), Src Ports: any, Dst IPs: IPv4Network('0.0.0.0/0'), Dst Ports: any, Message: {'msg': 'ICMP FLOOD DETECT', 'tos': None, 'len': None, 'offset': None, 'seq': None, 'ack': None, 'flags': 'S', 'time': 5, 'count': 100}",

    "ssh_brute_force": "Action: Action.ALERT, Protocol: Protocol.TCP, Src IPs: IPv4Network('0.0.0.0/0'), Src Ports: any, Dst IPs: IPv4Network('0.0.0.0/0'), Dst Ports: any, Message: {'msg': 'SSH BRUTEFORCE DETECT', 'tos': None, 'len': None, 'offset': None, 'seq': None, 'ack': None, 'flags': 'S', 'time': 5, 'count': 100}",

    "ftp_brute_force": "Action: Action.ALERT, Protocol: Protocol.TCP, Src IPs: IPv4Network('0.0.0.0/0'), Src Ports: any, Dst IPs: IPv4Network('0.0.0.0/0'), Dst Ports: any, Message: {'msg': 'FTP BRUTEFORCE DETECT','time': 5, 'count': 100, 'flow' : 'to_server'}",

    "arp_spoofing": "Action: Action.ALERT, Protocol: Protocol.ARP, Src IPs: any, Src Ports: any, Dst IPs: any, Dst Ports: any, Message: {'msg': 'ARP SPOOFING DETECTED', 'hwsrc': None, 'psrc': None, 'hwdst': None, 'pdst': None, 'op': 2, 'time': 1, 'count': 5}",

}





def packet_callback(packet,id):



    if IP in packet and packet[IP].src != host_ip:



        src_ip = packet[IP].src



        if TCP in packet:



            dst_port = packet[TCP].dport



            if packet[TCP].flags == "S":



                if event_counters["syn_scan"].add_and_check_event(src_ip):



                    logMessage = getMatchedMessage1(event_rule["syn_scan"])



                    log_alert(logMessage, packet,id)



                    logMessage += "By packet :\n" + packetString(packet) + "\n"



                    send_email_alert(logMessage)



            elif dst_port == 22:



                if event_counters["ssh_brute_force"].add_and_check_event(src_ip):



                    logMessage = getMatchedMessage1(event_rule["ssh_brute_force"])



                    log_alert(logMessage, packet,id)



                    logMessage += "By packet :\n" + packetString(packet) + "\n"



                    send_email_alert(logMessage)



            elif dst_port == 21:



                if event_counters["ftp_brute_force"].add_and_check_event(src_ip):



                    logMessage = getMatchedMessage1(event_rule["ftp_brute_force"])



                    log_alert(logMessage, packet,id)



                    logMessage += "By packet :\n" + packetString(packet) + "\n"



                    send_email_alert(logMessage)



        elif UDP in packet:



            if event_counters["udp_scan"].add_and_check_event(src_ip):



                logMessage = getMatchedMessage1(event_rule["udp_scan"])



                log_alert(logMessage, packet,id)



                logMessage += "By packet :\n" + packetString(packet) + "\n"



                send_email_alert(logMessage)



        elif ICMP in packet:



            if event_counters["icmp_sweep"].add_and_check_event(src_ip):



                logMessage = getMatchedMessage1(event_rule["icmp_sweep"])



                log_alert(logMessage, packet,id)



                logMessage += "By packet :\n" + packetString(packet) + "\n"



                send_email_alert(logMessage)





class Sniffer(Thread):



    """Thread responsible for sniffing and detecting suspect packets."""





class Sniffer(Thread):

    def __init__(self, ruleList, interface):



        super().__init__()



        self.stopped = False



        self.ruleList = ruleList



        self.interface = interface



        # self.packet_queue = queue.Queue()



        self.db = mysql.connector.connect(

            host="192.168.1.10", user="abdou", passwd="abdou", database="ids"

        )



        self.cursor = self.db.cursor()



        # Get the maximum packet_id from packets_log and start from the next value



        self.cursor.execute("SELECT MAX(packet_id) FROM packet_logs")



        max_id = self.cursor.fetchone()[0]



        self.packet_id = 1 if max_id is None else max_id + 1



        # self.executor = ThreadPoolExecutor(max_workers=100)



    def stop(self):



        self.stopped = True



    def inPacket(self, pkt):



        if IP in pkt:



            source_ip, destination_ip = pkt[IP].src, pkt[IP].dst



            protocol_map = {1: "ICMP", 6: "TCP", 17: "UDP"}



            protocol = pkt[IP].proto



            protocol_name = protocol_map.get(protocol, "Unknown")



            timestamp = datetime.now()



            # Initialize default values



            source_port = random.randint(5000, 9999)
            destination_port = random.randint(5000, 9999)




            ip_info = ipString(pkt[IP])



            transport_info, application_info = "", ""



            if TCP in pkt:



                source_port, destination_port = pkt[TCP].sport, pkt[TCP].dport



                transport_info = tcpString(pkt[TCP])



                application_info = "[TCP Payload]" + "\n" + payloadString(pkt)



            elif UDP in pkt:



                source_port, destination_port = pkt[UDP].sport, pkt[UDP].dport



                transport_info = udpString(pkt[UDP])



                application_info = "[UDP Payload]" + "\n" + payloadString(pkt)



            elif ICMP in pkt:



                # Assuming icmpString function exists and formats ICMP packet details



                transport_info = icmpString(pkt[ICMP])



                application_info = "[ICMP Payload]" + "\n" + payloadString(pkt)



        # Database insert operation



            insert_query = """INSERT INTO packet_logs







                (packet_id, timestamp, source_ip, source_port, destination_ip, destination_port, protocol, transport_info, ip_info, application_info)







                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""



            try:



                self.cursor.execute(

                    insert_query,

                    (

                        self.packet_id,

                        timestamp,

                        source_ip,

                        source_port,

                        destination_ip,

                        destination_port,

                        protocol_name,

                        transport_info,

                        ip_info,

                        application_info,

                    ),

                )



                self.db.commit()



                self.packet_id += 1



            except mysql.connector.Error as err:



                print(f"Failed to insert record into MySQL table: {err}")



            # Rule matching and alerting



            packet_callback(pkt,self.packet_id-1)



            for rule in self.ruleList:



                matched = rule.match(pkt)



                if matched:



                    logMessage = rule.getMatchedMessage()



                    log_alert(logMessage, pkt,self.packet_id-1)



                    logMessage += "By packet :\n" + packetString(pkt) + "\n"



                    send_email_alert(logMessage)
                    break



    def run(self):



        print(f"Sniffing started on interface: {self.interface}")



        sniff(

            prn=self.inPacket,

            filter="",

            iface=self.interface,

            store=0,

            stop_filter=lambda x: self.stopped,

        )


