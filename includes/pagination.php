<?php $page_name = basename($_SERVER['PHP_SELF']); ?>
<nav>
  <ul class="pagination pagination-sm">
    <?php
    if ($pagination['previous_page']<1){
        echo '<li class="page-item disabled">'
        . '<span class="page-link">Previous</span></li>';
    }else{
        echo '<li class="page-item">'
        . '<a class="page-link" href="'.$page_name.'?page='.$pagination['previous_page'].'" tabindex="-1">Previous</a></li>';
    }
    for($page=1;$page<=$pagination['num_pages'];$page++){
        if ($page==$pagination['present_page']){
            echo '<li class="page-item disabled">'
            . '<span class="page-link"> ' . $page . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="'.$page_name.'?page=' . $page . '"> ' . $page . '</a></li>';
        }
    }
    if ($pagination['next_page']>$pagination['num_pages']){
        echo '<li class="page-item disabled">'
        . '<span class="page-link">Next</span></li>';
    }else{
        echo '<li class="page-item">'
        . '<a class="page-link" href="'.$page_name.'?page='.$pagination['next_page'].'" tabindex="-1">Next</a></li>';
    } ?>
    </li>
  </ul>
</nav>
