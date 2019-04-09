import pymysql as pd
import  datetime

def DBconn(moisture):

  with open("creds.txt") as r:
    lines  = r.readlines()
    uname = lines[0]
    passwd = lines[1]
    host = lines[2]
    r.close()

  uname = uname.rstrip()
  passwd = passwd.rstrip()
  host = host.rstrip()
  dbname = "moisture_info"
  date = datetime.datetime.today().strftime('%Y-%m-%d %H:%M')
  stat = ("INSERT INTO moisturelog (date, moisture) VALUES (%s,%s)",(date,moisture))

  conn = pd.connect(host, user=uname, port=3306, passwd=passwd, db=dbname)
  curs = conn.cursor()
  curs.execute(*stat)
  conn.commit()
  conn.close()

