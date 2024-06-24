from ipaddress import *
from scapy.all import *
from scapy.layers.inet import *
from Utils import *
from Action import *
from Protocol import *
from IPNetwork import *
from Ports import *
from PacketStrings import *

def search_payload_for_content(pkt, search):
    """
    Searches for HTTP content within the packet payload (case-insensitive).

    Logs packets only if they contain the specified content or have a payload.
    """

    # Ensure the packet has a payload
    if not pkt.haslayer(Raw):
        return False  # No payload to search

    payload = pkt[Raw].load

    try:
        # Attempt to decode payload as UTF-8
        payload_str = payload.decode('utf-8').lower()  # Force lowercase conversion

        # Search for HTTP (case-insensitive) within decoded payload
        if search.lower() in payload_str:  # Force lowercase on search term
            return True  # HTTP content found

    except UnicodeDecodeError:
        # Payload couldn't be decoded; it might be binary or encrypted
        # Optionally, search for binary pattern of HTTP if necessary
        # For simplicity, this step is skipped here
        pass

    return False  # No HTTP content found


class Rule:
    """A NIDS rule."""

    def __init__(self, row):
        """Construct a rule from a string."""
       	
        rule_id,action1, protocol1, src_ip1, src_port1, dest_ip1, dest_port1, message = row
        self.id = rule_id  # Assign the unique rule ID

        self.action = action(action1)
        self.protocol = protocol(protocol1)

        # source ip and ports
        try:
            self.srcIps = IPNetwork(src_ip1)
        except:
            raise ValueError("Invalid rule : incorrect source ips : '" + src_ip1 + "'.")
        try:
            self.srcPorts = Ports(src_port1)
        except:
            raise ValueError("Invalid rule : incorrect source ports : '" + src_port1 + "'.")

        # destination ip and ports
        try:
            self.dstIps = IPNetwork(dest_ip1)
        except:
            raise ValueError("Invalid rule : incorrect destination ips : '" + dest_ip1 + "'.")

        try:
            self.dstPorts = Ports(dest_port1)
        except:
            raise ValueError("Invalid rule : incorrect destination ports : '" + dest_port1 + "'.")

        # Initialize options
        self.options = {
            "msg": None,
            "tos": None,
            "len": None,
            "offset": None,
            "seq": None,
            "ack": None,
            "flags": None,
            "http_request": None,
            "content": None,
        }

        # Parse options from the message
        opts = message.split(';')
        for opt in opts:
            kv = opt.split(':', 1)
            if len(kv) >= 2:
                option, value = kv[0].strip(), kv[1].strip()
                if option in self.options:
                    self.options[option] = value.strip('"')  # Remove starting and ending quotes if present

    def display(self):
        """Prints the rule with its options in a readable format."""
        rule_str = f"Action: {self.action}, Protocol: {self.protocol}, Src IPs: {self.srcIps}, Src Ports: {self.srcPorts}, Dst IPs: {self.dstIps}, Dst Ports: {self.dstPorts}"
        options_str = ", ".join(f"{key}: {value}" for key, value in self.options.items() if value is not None)
        print(f"{rule_str}, Options: {options_str}")

    def __repr__(self):
        """Returns the string representing the Rule"""
        return f"Action: {self.action}, Protocol: {self.protocol}, Src IPs: {self.srcIps}, Src Ports: {self.srcPorts}, Dst IPs: {self.dstIps}, Dst Ports: {self.dstPorts}, Message: {self.options}"
    def match(self, pkt):
        """
        Returns True if and only if the rule is matched by given packet,
        i.e. if every part of the rule is met by the packet.
        """
        # check protocol
        if (not self.checkProtocol(pkt)):
            return False

        # check IP source and destination
        if (not self.checkIps(pkt)):
            return False

        # check source Port
        if (not self.checkPorts(pkt)):
            return False

        # check options
        if (not self.checkOptions(pkt)):
            return False

        # otherwise the rule is met
        return True

    def checkProtocol(self, pkt):
        """ Returns True if and only if the rule concerns packet's protocol """
        f = False
        if (self.protocol == Protocol.TCP and TCP in pkt):
            f = True
        elif (self.protocol == Protocol.UDP and UDP in pkt):
            f = True
        elif (self.protocol == Protocol.HTTP and TCP in pkt):
            # HTTP packet has to be TCP
            # check payload to determine if this is a HTTP packet
            if (isHTTP(pkt)):
                f = True
        return f

    def checkIps(self, pkt):
        """Returns True if and only if the rule's IPs concern the pkt IPs"""
        f = False
        if (IP not in pkt):
            f = False
        else:
            srcIp = pkt[IP].src
            dstIp = pkt[IP].dst
            ipSrc = ip_address(str(srcIp))
            ipDst = ip_address(str(dstIp))
            if (self.srcIps.contains(ipSrc) and self.dstIps.contains(ipDst)):
                # ipSrc and ipDst match rule's source and destination ips
                f = True
            else:
                f = False
        return f

    def checkPorts(self, pkt):
        """Returns True if and only if the rule's Ports concern packet's Ports"""
        f = False
        if (UDP in pkt):
            srcPort = pkt[UDP].sport
            dstPort = pkt[UDP].dport
            if (self.srcPorts.contains(srcPort) and self.dstPorts.contains(dstPort)):
                f = True
        elif (TCP in pkt):
            srcPort = pkt[TCP].sport
            dstPort = pkt[TCP].dport
            if (self.srcPorts.contains(srcPort) and self.dstPorts.contains(dstPort)):
                f = True
        return f

    def checkOptions(self, pkt):
        """ Return True if and only if all options are matched """

        # if (hasattr(self, "tos")):
        #     if (IP in pkt):
        #         if (self.tos != int(pkt[IP].tos)):
        #             return False
        #     else:
        #         return False

        # if (hasattr(self, "len")):
        #     if (IP in pkt):
        #         if (self.len != int(pkt[IP].ihl)):
        #             return False
        #     else:
        #         return False

        # if (hasattr(self, "offset")):
        #     if (IP in pkt):
        #         if (self.offset != int(pkt[IP].frag)):
        #             return False
        #     else:
        #         return False

        # if (hasattr(self, "seq")):
        #     if (TCP not in pkt):
        #         return False
        #     else:
        #         if (self.seq != int(pkt[TCP].seq)):
        #             return False

        # if (hasattr(self, "ack")):
        #     if (TCP not in pkt):
        #         return False
        #     else:
        #         if (self.ack != int(pkt[TCP].ack)):
        #             return False

        # if (hasattr(self, "flags")):
        #     # match if and only if the received packet has all the rule flags set
        #     if (TCP not in pkt):
        #         return False
        #     else:
        #         for c in self.flags:
        #             pktFlags = pkt[TCP].underlayer.sprintf("%TCP.flags%")
        #             if (c not in pktFlags):
        #                 return False

        # if (hasattr(self, "http_request")):
        #     if (not isHTTP(pkt)):
        #         return False
        #     elif (TCP in pkt and pkt[TCP].payload):
        #         data = str(pkt[TCP].payload)
        #         words = data.split(' ')
        #         if ((len(words) < 1) or (words[0].rstrip() !=  self.http_request)):
        #             return False
        #     else:
        #         return False
        if "content" in self.options and self.options["content"]:
            # If the rule specifies content, ensure the packet contains the specified content.
            return search_payload_for_content(pkt,self.options["content"])

    def getMatchedMessage(self):
        """Return the message to be logged when the packet triggered the rule."""

        msg = ""
        if (self.action == Action.ALERT):
            msg += " ALERT "
        if hasattr(self, "msg"):
            msg += self.msg  + "\n"

        msg += "Rule matched :\n" + str(self) + "\n"
        return msg
        
        

