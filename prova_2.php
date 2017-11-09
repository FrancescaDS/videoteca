<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Remove White Space from Strings</title>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var myStr = $(".original").text();
        var trimStr = $.trim(myStr);
        $(".trimmed").html(trimStr);
    });
</script>
</head>
<body>
    <h3>Original String</h3>
    <pre class="original">      Paragraph of text with       multiple    white   spaces before and after.       </pre>
    <br>
    <h3>Trimmed String</h3>
    <pre class="trimmed"></pre>
</body>
</html>