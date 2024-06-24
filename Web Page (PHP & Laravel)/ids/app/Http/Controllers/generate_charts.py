import sys
import json
import matplotlib.pyplot as plt

def plot_top_ips(data, title, filename):
    labels = [item['source_ip'] for item in data]
    values = [item['count'] for item in data]

    plt.figure(figsize=(10, 5))
    plt.bar(labels, values, color='skyblue')
    plt.xlabel('IP Addresses')
    plt.ylabel('Number of Packets')
    plt.title(title)
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.savefig(filename)
    plt.close()

def plot_top_destination_ips(data, title, filename):
    labels = [item['destination_ip'] for item in data]
    values = [item['count'] for item in data]

    plt.figure(figsize=(10, 5))
    plt.bar(labels, values, color='skyblue')
    plt.xlabel('IP Addresses')
    plt.ylabel('Number of Packets')
    plt.title(title)
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.savefig(filename)
    plt.close()

def plot_top_source_ports(data, title, filename):
    labels = [item['source_port'] for item in data]
    values = [item['count'] for item in data]

    plt.figure(figsize=(10, 5))
    plt.bar(labels, values, color='skyblue')
    plt.xlabel('Source Ports')
    plt.ylabel('Number of Packets')
    plt.title(title)
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.savefig(filename)
    plt.close()

def plot_top_destination_ports(data, title, filename):
    labels = [item['destination_port'] for item in data]
    values = [item['count'] for item in data]

    plt.figure(figsize=(10, 5))
    plt.bar(labels, values, color='skyblue')
    plt.xlabel('Destination Ports')
    plt.ylabel('Number of Packets')
    plt.title(title)
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.savefig(filename)
    plt.close()

def plot_protocol_distribution(data, filename):
    labels = [item['protocol'] for item in data]
    values = [item['count'] for item in data]

    plt.figure(figsize=(10, 5))
    plt.pie(values, labels=labels, autopct='%1.1f%%', startangle=140, colors=['skyblue', 'lightgreen', 'lightcoral', 'gold'])
    plt.title('Protocol Distribution')
    plt.tight_layout()
    plt.savefig(filename)
    plt.close()

if __name__ == '__main__':
    try:
        with open(sys.argv[1], 'r') as f:
            data = json.load(f)
            top_source_ips = data['topSourceIPs']
            top_destination_ips = data['topDestinationIPs']
            top_source_ports = data['topSourcePorts']
            top_destination_ports = data['topDestinationPorts']
            protocol_distribution = data['protocolDistribution']
    except json.JSONDecodeError as e:
        print(f"JSON decoding failed: {e}")
        sys.exit(1)

    plot_top_ips(top_source_ips, 'Top Source IPs', 'top_source_ips.png')
    plot_top_destination_ips(top_destination_ips, 'Top Destination IPs', 'top_destination_ips.png')
    plot_top_source_ports(top_source_ports, 'Top Source Ports', 'top_source_ports.png')
    plot_top_destination_ports(top_destination_ports, 'Top Destination Ports', 'top_destination_ports.png')
    plot_protocol_distribution(protocol_distribution, 'protocol_distribution.png')

    print('top_source_ips.png,top_destination_ips.png,top_source_ports.png,top_destination_ports.png,protocol_distribution.png')
