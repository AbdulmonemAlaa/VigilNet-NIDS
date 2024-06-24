import re
from scapy.all import *
from Utils import *
from Rule import *
from scapy.all import IP, TCP, UDP,IPv6

URG = 0x20



def icmpString(icmp):
    """Construct the human-readable string corresponding to the ICMP header."""
    out = "[ICMP HEADER]" + "\n"
    out += "\t Type: " + str(icmp.type) + "\n"
    out += "\t Code: " + str(icmp.code) + "\n"
    out += "\t Checksum: " + str(icmp.chksum) + "\n"
    if icmp.type == 0 or icmp.type == 8:  # Echo request and reply
        out += "\t Identifier: " + str(icmp.id) + "\n"
        out += "\t Sequence Number: " + str(icmp.seq) + "\n"
    return out



def ipString(ip):
    """Construct the human-readable string corresponding to the IP header."""

    out = "[IP HEADER]" + "\n"
    out += "\t Version: " + str(ip.version) + "\n"
    out += "\t IHL: " + str(ip.ihl * 4) + " bytes" + "\n"
    out += "\t ToS: " + str(ip.tos) + "\n"
    out += "\t Total Length: " + str(ip.len) + "\n"
    out += "\t Identification: " + str(ip.id) + "\n"
    out += "\t Flags: " + str(ip.flags) + "\n"
    out += "\t Fragment Offset: " + str(ip.frag) + "\n"
    out += "\t TTL: " + str(ip.ttl) + "\n"
    out += "\t Protocol: " + str(ip.proto) + "\n"
    out += "\t Header Checksum: " + str(ip.chksum) + "\n"
    out += "\t Source: " + str(ip.src) + "\n"
    out += "\t Destination: " + str(ip.dst) + "\n"
    if (ip.ihl > 5):
        out += "\t Options: " + str(ip.options) + "\n"
    return out

def matchedIpString(ip, rule):
    """Construct the human-readable string corresponding to the matched IP header, with matched fields in red."""

    out = "[IP HEADER]" + "\n"
    out += "\t Version: " + str(ip.version) + "\n"
    if (hasattr(rule, "len")):
        out += "\t IHL: " + str(ip.ihl * 4) + " bytes" + "\n"
    else:
        out += "\t IHL: " + str(ip.ihl * 4) + " bytes" + "\n"
    if (hasattr(rule, "tos")):
        out += "\t ToS: " + str(ip.tos) +  "\n"
    else:
        out += "\t ToS: " + str(ip.tos) + "\n"

    out += "\t Total Length: " + str(ip.len) + "\n"
    out += "\t Identification: " + str(ip.id) + "\n"
    out += "\t Flags: " + str(ip.flags) + "\n"


    if (hasattr(rule, "offset")):
        out += "\t Fragment Offset: " + str(ip.frag) + "\n"
    else:
        out += "\t Fragment Offset: " + str(ip.frag) + "\n"

    out += "\t TTL: " + str(ip.ttl) + "\n"
    out += "\t Protocol: " + str(ip.proto) + "\n"
    out += "\t Header Checksum: " + str(ip.chksum) + "\n"

    # If the IP was specified uniquely, out += red
    if (rule.srcIps.ipn.num_addresses == 1):
        out +="\t Source: " + str(ip.src) +  "\n"
    else:
        out += "\t Source: " + str(ip.src) + "\n"

    if (rule.dstIps.ipn.num_addresses == 1):
        out +="\t Destination: " + str(ip.dst) + "\n"
    else:
        out += "\t Destination: " + str(ip.dst) + "\n"

    if (ip.ihl > 5):
        out += "\t Options : " + str(ip.options) + "\n"
    return out

def tcpString(tcp):
        """Construct the human-readable string corresponding to the TCP header."""

        out = "[TCP Header]" + "\n"
        out += "\t Source Port: " + str(tcp.sport) + "\n"
        out += "\t Destination Port: " + str(tcp.dport) + "\n"
        out += "\t Sequence Number: " + str(tcp.seq) + "\n"
        out += "\t Acknowledgment Number: " + str(tcp.ack) + "\n"
        out += "\t Data Offset: " + str(tcp.dataofs) + "\n"
        out += "\t Reserved: " + str(tcp.reserved) + "\n"
        out += "\t Flags: " + tcp.underlayer.sprintf("%TCP.flags%") + "\n"
        out += "\t Window Size: " + str(tcp.window) + "\n"
        out += "\t Checksum: " + str(tcp.chksum) + "\n"
        if (tcp.flags & URG):
            out += "\t Urgent Pointer: " + str(tcp.window) + "\n"
        if (tcp.dataofs > 5):
            out += "\t Options: " + str(tcp.options) + "\n"
        return out

def matchedTcpString(tcp, rule):
    """Construct the human-readable string corresponding to the matched TCP header, with matched fields in red."""

    out = "[TCP Header]" + "\n"
    if (hasattr(rule.srcPorts, "listPorts") and len(rule.srcPorts.listPorts) == 1):
        out +=  "\t Source Port: " + str(tcp.sport) +"\n"
    else:
        out += "\t Source Port: " + str(tcp.sport) + "\n"
    if (hasattr(rule.dstPorts, "listPorts") and len(rule.dstPorts.listPorts) == 1):
        out +=  "\t Destination Port: " + str(tcp.dport) + "\n"
    else:
        out += "\t Destination Port: " + str(tcp.dport) + "\n"
    if (hasattr(rule, "seq")):
        out += "\t Sequence Number: " + str(tcp.seq) + "\n"
    else:
        out += "\t Sequence Number: " + str(tcp.seq) + "\n"
    if (hasattr(rule, "ack")):
        out +=  "\t Acknowledgment Number: " + str(tcp.ack) +  "\n"
    else:
        out += "\t Acknowledgment Number: " + str(tcp.ack) + "\n"
    out += "\t Data Offset: " + str(tcp.dataofs) + "\n"
    out += "\t Reserved: " + str(tcp.reserved) + "\n"
    if (hasattr(rule,"flags")):
        out += "\t Flags:" + tcp.underlayer.sprintf("%TCP.flags%") + "\n"
    else:
        out += "\t Flags:" + tcp.underlayer.sprintf("%TCP.flags%") + "\n"
    out += "\t Window Size: " + str(tcp.window) + "\n"
    out += "\t Checksum: " + str(tcp.chksum) + "\n"
    if (tcp.flags & URG):
        out += "\t Urgent Pointer: " + str(tcp.window) + "\n"
    if (tcp.dataofs > 5):
        out += "\t Options: " + str(tcp.options) + "\n"
    return out

def udpString(udp):
    """Construct the human-readable string corresponding to the UDP header."""

    out = "[UDP Header]" + "\n"
    out += "\t Source Port: " + str(udp.sport) + "\n"
    out += "\t Destination Port: " + str(udp.dport) + "\n"
    out += "\t Length: " + str(udp.len) + "\n"
    out += "\t Checksum: " + str(udp.chksum) + "\n"
    return out

def matchedUdpString(udp, rule):
    """Construct the human-readable string corresponding to the UDP header, with matched fields in red."""

    out = "[UDP Header]" + "\n"
    if (hasattr(rule.srcPorts, "listPorts") and len(rule.srcPorts.listPorts) == 1):
        out +=  "\t Source Port: " + str(udp.sport) +  "\n"
    else:
        out += "\t Source Port: " + str(udp.sport) + "\n"
    if (hasattr(rule.dstPorts, "listPorts") and len(rule.dstPorts.listPorts) == 1):
        out +=  "\t Destination Port: " + str(udp.dport) + "\n"
    else:
        out += "\t Destination Port: " + str(udp.dport) + "\n"
    out += "\t Length: " + str(udp.len) + "\n"
    out += "\t Checksum: " + str(udp.chksum) + "\n"
    return out



def insert_newlines_and_tabs(text, is_hex=False):
    """Inserts newlines after every 100 characters in the text, adding tabs at the start of all lines."""

    def split_and_add_tabs(line, step):
        """Splits a line into a specified step and adds tabs."""
        return "\n\t".join(line[i:i+step] for i in range(0, len(line), step))

    # Split existing text by newlines, then process each line to insert newlines and tabs as needed
    step = 50 if is_hex else 100  # Adjust for hex representation (2 characters per byte)
    processed_lines = []
    for original_line in text.split('\n'):
        processed_line = split_and_add_tabs(original_line, step)
        processed_lines.append(processed_line)

    # For hex, add space between every two characters for readability
    if is_hex:
        processed_lines = [' '.join(line[i:i+2] for i in range(0, len(line), 2)) for line in processed_lines]

    return "\n\t".join(processed_lines)

def payloadString(pkt):
    """Construct the human-readable string corresponding to the payload."""
    if hasattr(pkt, 'load') and pkt.load:
        try:
            # Attempt to decode payload as string
            payload_str = pkt.load.decode('utf-8')
            # Format string payload: Split and add tabs
            if payload_str.strip():  # Ensure the decoded string is not empty
                formatted_str = insert_newlines_and_tabs(payload_str)
                return " \t" + formatted_str  # Initial tab for the first line
        except UnicodeDecodeError:
            # Decode payload as hex if UTF-8 fails
            payload_hex = pkt.load.hex()
            if payload_hex.strip():
                # Format hex payload: Split and add tabs
                formatted_hex = insert_newlines_and_tabs(payload_hex, is_hex=True)
                return " \t" + formatted_hex  # Initial tab for the first line
    return " \tNo payload.\n"  # For payloads that are not present or cannot be decoded






def packetString(pkt):
    """Construct the human-readable string corresponding to the packet, from IP header to Application data."""

    out = ""
    if (IP in pkt):
        out += ipString(pkt[IP])
    elif (IPv6 in pkt):
        # TODO
        pass
    if (TCP in pkt):
        out += tcpString(pkt[TCP])
        out += "[TCP Payload]" + "\n"
        out+= payloadString(pkt)
    elif (UDP in pkt):
        out += udpString(pkt[UDP])
        out += "[UDP Payload]" + "\n"
        out += payloadString(pkt)
    elif (ICMP in pkt):
        out += icmpString(pkt[ICMP])
    return out



def matchedPacketString(pkt, rule):
    """Construct the human-readable string corresponding to the matched packet, from IP header to Application data, with matched fields in red."""

    out = ""
    if (IP in pkt):
        # IP Header
        out += matchedIpString(pkt[IP], rule)
    elif (IPv6 in pkt):
        # TODO
        pass
    if (TCP in pkt):
        # TCP Header
        out += matchedTcpString(pkt[TCP], rule)
        # Payload
        out += payloadString(pkt)

    elif (UDP in pkt):
        out += matchedUdpString(pkt[UDP], rule)
        out += payloadString(pkt)
    elif (UDP in pkt):
        out += matchedUdpString(pkt[UDP], rule)
        out += payloadString(pkt)
    return out
