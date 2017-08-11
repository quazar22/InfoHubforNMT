def main():
    input_file = open('fileOne.txt', 'r')
    output_file = open('fileout.txt', 'a')
    for line in input_file:
        if "<td valign=\"top\" width=\"18%\" no wrap>" in line:
            f = str(line.split()[7])
            g = str(line.split()[6])
            g = g[:-1]
            output_file.write(f)
            output_file.write(' ')
            output_file.write(g)
            output_file.write('\n')
        if "<td valign=\"top\" width=\"10%\" no wrap>" in line:
            f = line.split()[4]
            f = f[5:]
            if "-" in line:
                output_file.write("575" + "-" + str(f))
                output_file.write('\n')
            else:
                output_file.write("575-835-" + str(f))
                output_file.write('\n')
        """if "<td valign=\"top\" width=\"24%\" no wrap>" in line:
            f = line[41:]
            f = f[:-6]
            output_file.write(f)
            output_file.write('\n')"""
        if "<td valign=\"top\" width=\"14%\" no wrap>" in line:
            f = line[41:]
            f = f[:-6]
            f = f.replace("&nbsp;"," ")
            output_file.write(f)
            output_file.write('\n')
        if "<td valign=\"top\" width=\"12%\" no wrap>" in line:
            f = line.split()
            f = f[6]
            f = f[:-9]
            output_file.write(f)
            output_file.write('\n')
            output_file.write('\n')
    input_file.close()
    output_file.close()
if __name__ == "__main__":
    main()
