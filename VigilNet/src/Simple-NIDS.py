from sys import argv




import RuleFileReader

from Sniffer import Sniffer



"""Read the rule file and start listening."""




print("Simple-NIDS started.")

    # Read the rule file

print("Reading rule file...")

ruleList, errorCount = RuleFileReader.read()

print("Finished reading rule file.")

if errorCount == 0:

    print("All (" + str(len(ruleList)) + ") rules have been correctly read.")

else:

    print(str(len(ruleList)) + " rules have been correctly read.")

    print(str(errorCount) + " rules have errors and could not be read.")



    # Begin sniffing

sniffer = Sniffer(ruleList,interface="enp0s3")

sniffer.start()

try:

        # Keep the script running

    while True:

        pass

except KeyboardInterrupt:

        # Stop the sniffer when Ctrl+C is pressed

    sniffer.stop()

    print("Sniffer stopped by user (Ctrl+C)")


