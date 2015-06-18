<?php  
function printList($tree)
{
    echo "<ul>";
    foreach ($tree as $v) {
        if (checkChild($v)) {
            echo "<li>";
            echo '<span> <i class="fa fa-folder-open"> </i> 节点
                </span><a id="' . $v["id"] . '"> ' . $v["cName"] . '</a>';
            printList($v['child']);
            echo "</li>";
        } else {
            echo "<li >";
             echo '<span> <i class="fa fa-folder-open"> </i> 节点
                </span><a id="' . $v["id"] . '"> ' . $v["cName"] . '</a>';
            echo "</li>";
        }
    }
    echo "</ul>";
}

function checkChild($array)
{
    if (array_key_exists("child", $array)) {
        return true;
    } else {
        return false;
    }
}