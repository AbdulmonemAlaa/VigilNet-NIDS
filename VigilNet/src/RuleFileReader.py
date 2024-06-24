"""Functions for reading a file of rules."""

from Rule import *
import mysql.connector

db = mysql.connector.connect(
  host="192.168.1.10",
  user="abdou",
  passwd="abdou",
  database="ids"
)
cursor = db.cursor()
def read():
    
    l = list()

    ruleErrorCount = 0
    # Example query
    cursor.execute("SELECT * FROM signatures")
    for row in cursor:
        try:
            rule = Rule(row)
            l.append(rule)
        
        except ValueError as err:
            ruleErrorCount += 1
            print (err)

    return ( l, ruleErrorCount)

