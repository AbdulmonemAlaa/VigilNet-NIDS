from ipaddress import *

class IPNetwork:
    """An IP network with CIDR block. Represents a set of IPs."""

    from ipaddress import ip_network

class IPNetwork:
    """An IP network with CIDR block. Represents a set of IPs."""

    def __init__(self, string):
        """Construct an IPNetwork from a string like 'a.b.c.d/e', 'a.b.c.d' or 'any'."""
        try:
            if string.rstrip() == "any":
                self.ipn = ip_network('0.0.0.0/0', strict=False)
            else:
                self.ipn = ip_network(string, strict=False)
        except ValueError as e:
            raise ValueError(f"Incorrect input string: {e}")

    def contains(self, ip):
        """Check if the input IP is in the IPNetwork, return True if yes."""

        return ip in self.ipn

    def __repr__(self):
        """String representation of the IPNetwork"""

        return self.ipn.__repr__()
