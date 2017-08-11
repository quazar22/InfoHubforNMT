import pysftp
from tkinter import *
import datetime
import os

class make_ui(Tk):
    date_list = list()
    cur_date = datetime.datetime.now()
    for i in range(0, 7):
        date_list.append((cur_date + datetime.timedelta(days=i)).strftime('%B %d, %Y'))

    def __init__(self):
        Tk.__init__(self)
        self.wm_title("Event Adder")
        self.geometry("300x350")
        frame = Frame(self)
        frame.pack()
        lb_pane = PanedWindow(frame, orient=HORIZONTAL)
        ins_pane = PanedWindow(frame, orient=HORIZONTAL)
        buttons = PanedWindow(frame, orient=HORIZONTAL)
        lb_pane.pack()
        ins_pane.pack()
        buttons.pack()
        lb = Listbox(lb_pane, width=30)
        lbl1 = StringVar()
        lbl2 = StringVar()
        lbl3 = StringVar()
        lbl_1 = Label(ins_pane, textvariable=lbl1)
        lbl_2 = Label(ins_pane, textvariable=lbl2)
        lbl_3 = Label(ins_pane, textvariable=lbl3)
        lbl1.set("Insert Event Name Here")
        lbl2.set("Insert Time Range Here")
        lbl3.set("Insert Event Description Here")
        self.text = Text(ins_pane, height=1, width=34)
        self.text2 = Text(ins_pane, height=1, width=34)
        self.text3 = Text(ins_pane, height=3, width=34)
        lbl_1.pack()
        self.text.pack()
        lbl_2.pack()
        self.text2.pack()
        lbl_3.pack()
        self.text3.pack()
        add_button = Button(buttons, text="Add Event", command=self.add_event)
        add_button.pack()
        for i in range(len(self.date_list)):
            lb.insert(i, self.date_list[i])
        lb_pane.add(lb)
        lb.bind("<Double-Button-1>", self.OnDouble)
        lb.pack()

    def OnDouble(self, event):
        widget = event.widget
        selection = widget.curselection()
        value = widget.get(selection[0])
        self.value = value

    def connect(self, config):
        if(config == 0):
            with pysftp.Connection('sftp.nmt.edu', username='osl', password='Tutoring1!') as sftp:
                with sftp.cd('public_html'):
                    sftp.get('week_events.txt')
                    with open('week_events.txt', 'r') as fr:
                        self.content = fr.readlines()
        if(config == 1):
            with pysftp.Connection('sftp.nmt.edu', username='osl', password='Tutoring1!') as sftp:
                with sftp.cd('public_html'):
                    sftp.put('week_events.txt')
                    try:
                        os.remove('week_events.txt')
                    except OSError:
                        pass


    def add_event(self):
        self.connect(0)
        write = 0
        cnt = 0
        with open('week_events.txt', 'w') as fo:
            for line in self.content:
                if line.strip("\n") == self.value:
                    fo.write(line)
                    write += 1
                    continue
                if(write == 1):
                    if line != "\n":
                        if cnt == 1:
                            fo.write("%s\n" % line)
                            cnt = 0
                            continue
                        fo.write(line)
                        cnt += 1
                        continue
                    if line == "\n":
                        fo.write("%s | %s\n" % (self.text2.get("1.0","end-1c"), self.text.get("1.0","end-1c")))
                        fo.write("%s\n" % (self.text3.get("1.0","end-1c")))
                        fo.write(line)
                        write = 0
                        continue
                fo.write(line)
        self.connect(1)

if __name__ == "__main__":
    events_adder = make_ui()
    events_adder.mainloop()
