def main():
    letters = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"]
    for letter in letters:
        input_file = open('fileout.txt', 'r')
        header = open('header.txt', 'r')
        output_file = open(str(letter) + '.html', 'w')
        for head in header:
            output_file.write(head)
        i = 0
        for line in input_file:
            if i == 0:
                try:
                    if (str(line.split()[1])[0] == str(letter)) and len(line.split()) == 2:
                        output_file.write("<tr>\n<td style='text-align: center;' class='invis-box id just-max'><h5 style='color: #333;'>" + str(line).replace("\n","") + "</h5></td>")
                        i = i + 1
                except:
                    pass
            elif i == 1:
                output_file.write("<td colspan='1' class='table-box col-lg-2 col-md-2 col-sm-2 col-xs-2'><a href='javascript:void(0)' data-toggle='popover' data-trigger='focus' data-placement='top' class='table-box box-max' data-content=\"" + str(line).replace("\n","") + "\" ><h5>Phone</td></a></h5>")
                i = i + 1
            elif i == 2:
                output_file.write("<td colspan='1' class='table-box col-lg-2 col-md-2 col-sm-2 col-xs-2'><a href='javascript:void(0)' data-toggle='popover' data-trigger='focus' data-placement='top' class='table-box box-max' data-content=\"" + str(line).replace("\n","") + "\" ><h5>Office</td></a></h5>")
                i = i + 1
            elif i == 3:
                output_file.write("<td colspan='1' class='table-box col-lg-2 col-md-2 col-sm-2 col-xs-2'><a href='javascript:void(0)' data-toggle='popover' data-trigger='focus' data-placement='top' class='table-box box-max' data-content=\"" + str(line).replace("\n","") + "\" ><h5>E-Mail</td></a></h5>\n</tr>")
                i = i + 1
            else:
                i = 0
        print(letter)
        output_file.write("</tbody>\n</table>\n</div>\n</div>\n</div>\n</div>\n<script>\n$(function () { $('[data-toggle=\"popover\"]').popover()})\n</script>\n</body>\n</html>")
    input_file.close()
    header.close()
    output_file.close()
if __name__ == "__main__":
    main()
