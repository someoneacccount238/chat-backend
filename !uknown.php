function getMsgHistory()
{
$f = file_get_contents('notifs.json');
$item = json_decode($f, true);

//users
foreach ($item as $user) {
foreach ($user as $node) {
if (($node[2] == $_SESSION['email'] || substr($node[2], 0, -1) == $_SESSION['email'])

&& ($node[1] == $_GET['hist'] || substr($node[1], 0, -1) == $_GET['hist'])

) {
echo "
<div class=\"msg left-msg\">
    <div class=\"msg-img\" style=\"background-image: url(https://image.flaticon/icons/svg/327/327779.svg)\">

    </div>
    <div class=\"msg-bubble\">
        <div class=\"msg-info\">
            <div class=\"msg-info-name1\" style=\"color:blue\">
                <h5>" . '
                    <pre> ' . $node[1] . '</pre>' . "
                </h5>
            </div>
            <div class=\"msg-info-time\">\".$node[3].\"</div>
        </div>

        <div class=\"msg-text\">\".$node[0].\"</div>
    </div>
</div>";
} else if (($node[1] == $_SESSION['email'] || substr($node[1], 0, -1) == $_SESSION['email'])

&& ($node[2] == $_GET['hist'] || substr($node[2], 0, -1) == $_GET['hist'])
) {
echo "

<div class=\"msg right-msg\"
    <div class=\"msg-img\" style=\"background-image: url(https://image.flaticon/icons/svg/145/145867.svg)\">

</div>
<div class=\"msg-bubble\">
    <div class=\"msg-info\">
        <div class=\"msg-info-name2\" style=\"color:blue\">
            <h5>" . '
                <pre> ' . $node[1] . '</pre>' . "
            </h5>
        </div>
        <div class=\"msg-info-time\">\".$node[3].\"</div>
    </div>

    <div class=\"msg-text\">\".$node[0].\"</div>
</div>
</div>";
}
}
}
}
