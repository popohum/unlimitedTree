<?php
require_once './src/data.php';
require_once './src/buildTree.php';
require_once './src/tree.func.php';
$result = getData();
$tree = new Tree($result);
?>
<html>
<head>
    <style>
        * {
            font-family: 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', sans-serif;
        }

        .tree {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #fbfbfb;
            border: 1px solid #48B74D;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
            -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05)
        }

        .tree li {
            list-style-type: none;
            margin: 0;
            padding: 10px 5px 0 5px;
            position: relative
        }

        .tree li::before, .tree li::after {
            content: '';
            left: -20px;
            position: absolute;
            right: auto
        }

        .tree li::before {
            border-left: 1px solid #48b74d;
            bottom: 50px;
            height: 100%;
            top: 0;
            width: 1px
        }

        .tree li::after {
            border-top: 1px solid #48b74d;
            height: 20px;
            top: 25px;
            width: 25px
        }

        .tree li span {
            color: darkgreen;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border: 1px solid #48b74d;
            border-radius: 8px;
            display: inline-block;
            padding: 3px 10px;
            text-decoration: none
        }

        .tree li.parent_li > span {
            cursor: pointer
        }

        .tree > ul > li::before, .tree > ul > li::after {
            border: 0
        }

        .tree li:last-child::before {
            height: 30px
        }

        .tree li.parent_li > span:hover, .tree li.parent_li > span:hover + ul li span {
            background: #eee;
            border: 1px solid #48b74d;
            color: #1A7966
        }

        .tree a {
            color: #48b74d;
            text-decoration: none;
        }
    </style>
    <link href="http://cdn.bootcss.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="tree well">
    <?php
    if ($tree) {
        $tree = $tree->leaf();
        printList($tree);
    }
    ?>
</div>
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', '关闭节点');
        $('.tree ul li:not(.parent_li)').find(' > span > i ').addClass('fa-leaf').removeClass('fa-folder-open');
        $('.tree li a').attr('title', '修改分类');
        $('.tree li a').on('click',function () {
             alert('Node:'+$(this).attr('id')+'父节点：'+$(this).parent().parent().parent('ul>a'));
        });
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', '展开节点').find(' > i').addClass('fa-plus-square').removeClass('fa-minus-square');
            } else {
                children.show('fast');
                $(this).attr('title', '关闭节点').find(' > i').addClass('fa-minus-square').removeClass('fa-plus-square');
            }
            e.stopPropagation();
        });
    });
</script>
</body>
</html>