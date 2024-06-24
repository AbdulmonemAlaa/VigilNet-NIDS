from scapy.all import *
from Utils import *
from Rule import *

# TCP flags
FIN = 0x01
SYN = 0x02
RST = 0x04
PSH = 0x08
ACK = 0x10
URG = 0x20
ECE = 0x40
CWR = 0x80

# IPs
def displayIP(ip):
    """Display the IPv4 header"""
    print("[IP HEADER]")
    print("\t Version: " + str(ip.version))
    print("\t IHL: " + str(ip.ihl * 4) + " bytes")
    print("\t ToS: " + str(ip.tos))
    print("\t Total Length: " + str(ip.len))
    print("\t Identification: " + str(ip.id))
    print("\t Flags: " + str(ip.flags))
    print("\t Fragment Offset: " + str(ip.frag))
    print("\t TTL: " + str(ip.ttl))
    print("\t Protocol: " + str(ip.proto))
    print("\t Header Checksum: " + str(ip.chksum))
    print("\t Source: " + str(ip.src))
    print("\t Destination: " + str(ip.dst))
    if ip.ihl > 5:
        print("\t Options: " + str(ip.options))


def displayIPv6(ip):
    """Display the IPv6 header"""
    # TODO
    print("[IP HEADER]")
    print("\t Version: " + str(ip.version))
    print("\t Header Length: " + str(40) + " bytes")
    print("\t Flow Label: " + str(ip.fl))
    print("\t Traffic Class: " + str(ip.tc))
    print("\t Source: " + str(ip.src))
    print("\t Destination: " + str(ip.dst))


def displayMatchedIP(ip, rule):
    """Display the IPv4 header with matched fields."""
    print("[IP HEADER]")
    print("\t Version: " + str(ip.version))
    if hasattr(rule, "len"):
        print("\t IHL: " + str(ip.ihl * 4) + " bytes")
    else:
        print("\t IHL: " + str(ip.ihl * 4) + " bytes")
    if hasattr(rule, "tos"):
        print("\t ToS: " + str(ip.tos))
    else:
        print("\t ToS: " + str(ip.tos))
    print("\t Total Length: " + str(ip.len))
    print("\t Identification: " + str(ip.id))
    print("\t Flags: " + str(ip.flags))
    if hasattr(rule, "offset"):
        print("\t Fragment Offset: " + str(ip.frag))
    else:
        print("\t Fragment Offset: " + str(ip.frag))
    print("\t TTL: " + str(ip.ttl))
    print("\t Protocol: " + str(ip.proto))
    print("\t Header Checksum: " + str(ip.chksum))
    # If the IP was specified uniquely, print the source and destination in red
    if rule.srcIps.ipn.num_addresses == 1:
        print("\t Source: " + str(ip.src))
    else:
        print("\t Source: " + str(ip.src))
    if rule.dstIps.ipn.num_addresses == 1:
        print("\t Destination: " + str(ip.dst))
    else:
        print("\t Destination: " + str(ip.dst))
    if ip.ihl > 5:
        print("\t Options : " + str(ip.options))


# TCP
def displayTCP(tcp):
    """Display the TCP header."""
    print("[TCP Header]")
    print("\t Source Port: " + str(tcp.sport))
    print("\t Destination Port: " + str(tcp.dport))
    print("\t Sequence Number: " + str(tcp.seq))
    print("\t Acknowledgment Number: " + str(tcp.ack))
    print("\t Data Offset: " + str(tcp.dataofs))
    print("\t Reserved: " + str(tcp.reserved))
    print("\t Flags: " + tcp.underlayer.sprintf("%TCP.flags%"))
    print("\t Window Size: " + str(tcp.window))
    print("\t Checksum: " + str(tcp.chksum))
    if tcp.flags & URG:
        print("\t Urgent Pointer: " + str(tcp.window))
    if tcp.dataofs > 5:
        print("\t Options: " + str(tcp.options))

def displayMatchedTCP(tcp, rule):
    """Display the TCP header with matched fields."""
    print("[TCP Header]")
    if hasattr(rule.srcPorts, "listPorts") and len(rule.srcPorts.listPorts) == 1:
        print("\t Source Port: " + str(tcp.sport))
    else:
        print("\t Source Port: " + str(tcp.sport))
    if hasattr(rule.dstPorts, "listPorts") and len(rule.dstPorts.listPorts) == 1:
        print("\t Destination Port: " + str(tcp.dport))
    else:
        print("\t Destination Port: " + str(tcp.dport))
    if hasattr(rule, "seq"):
        print("\t Sequence Number: " + str(tcp.seq))
    else:
        print("\t Sequence Number: " + str(tcp.seq))
    if hasattr(rule, "ack"):
        print("\t Acknowledgment Number: " + str(tcp.ack))
    else:
        print("\t Acknowledgment Number: " + str(tcp.ack))
    print("\t Data Offset: " + str(tcp.dataofs))
    print("\t Reserved: " + str(tcp.reserved))
    if hasattr(rule, "flags"):
        print("\t Flags:" + tcp.underlayer.sprintf("%TCP.flags%"))
    else:
        print("\t Flags:" + tcp.underlayer.sprintf("%TCP.flags%"))
    print("\t Window Size: " + str(tcp.window))
    print("\t Checksum: " + str(tcp.chksum))
    if tcp.flags & URG:
        print("\t Urgent Pointer: " + str(tcp.window))
    if tcp.dataofs > 5:
        print("\t Options: " + str(tcp.options))

# UDP
def displayUDP(udp):
    """Display the UDP header."""
    print("[UDP Header]")
    print("\t Source Port: " + str(udp.sport))
    print("\t Destination Port: " + str(udp.dport))
    print("\t Length: " + str(udp.len))
    print("\t Checksum: " + str(udp.chksum))

# TODO : matched UDP ?
def displayMatchedUDP(udp, rule):
    """Display the UDP header with matched fields."""
    print("[UDP Header]")
    if hasattr(rule, "srcPorts") and len(rule.srcPorts.listPorts) == 1:
        print("\t Source Port: " + str(udp.sport))
    else:
        print("\t Source Port: " + str(udp.sport))
    if hasattr(rule, "dstPorts") and len(rule.dstPorts.listPorts) == 1:
        print("\t Destination Port: " + str(udp.dport))
    else:
        print("\t Destination Port: " + str(udp.dport))
    print("\t Length: " + str(udp.len))
    print("\t Checksum: " + str(udp.chksum))



def displayPayload(packet):
    # Check if packet has a load attribute
    if hasattr(packet, 'load'):
        try:
            # Attempt to decode payload as string
            payload_str = packet.load.decode('utf-8')
            print("Payload as string:", payload_str)
            print("="*40) 
        except UnicodeDecodeError:
            # Decode payload as hex if the above fails
            payload_hex = packet.load.hex()
            print("Payload as hex:", payload_hex)
            print("="*40) 
    else:
        print("No payload.")
        print("="*40) 

def displayICMP(icmp):
    """Display the ICMP packet details"""
    print("[ICMP HEADER]")
    print("\t Type: " + str(icmp.type))
    print("\t Code: " + str(icmp.code))
    print("\t Checksum: " + str(icmp.chksum))
    if icmp.type == 0 or icmp.type == 8:  # Echo request and reply
        print("\t Identifier: " + str(icmp.id))
        print("\t Sequence Number: " + str(icmp.seq))






# Whole packet
def printMatchedPacket(pkt, rule):
    """Display the whole packet from IP to Application layer."""
    if IP in pkt:
        # IP Header
        displayMatchedIP(pkt[IP], rule)
    elif IPv6 in pkt:
        displayIPv6(pkt[IPv6])
    if TCP in pkt:
        # TCP Header
        displayMatchedTCP(pkt[TCP], rule)
        # Payload
        displayPayload(pkt)
    elif UDP in pkt:
        displayUDP(pkt[UDP])
        print("[UDP Payload]")
        displayPayload(pkt)

def printPacket(pkt):
    if IP in pkt:
        displayIP(pkt[IP])
    elif IPv6 in pkt:
        displayIPv6(pkt[IPv6])
    if TCP in pkt:
        displayTCP(pkt[TCP])
        print("[TCP Payload]")
        displayPayload(pkt)
    elif UDP in pkt:
        displayUDP(pkt[UDP])
        print("[UDP Payload]")
        displayPayload(pkt)
    elif ICMP in pkt:
        displayICMP(pkt[ICMP])
