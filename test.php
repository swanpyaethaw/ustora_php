<?php for ($i=0; $i<5; $i++) { ?>
    <li>
        <span><?php echo 5-$i ?></span> => 
        <?php  
            $myarray = [
                ['name' => 1, 'value' => $one],
                ['name' => 2, 'value' => $two],
                ['name' => 3, 'value' => $three],
                ['name' => 4, 'value' => $four],
                ['name' => 5, 'value' => $five],
            ];
            $index = array_search(5-$i, array_column($myarray, 'name'));
            if ($myarray[$index]['value'] && $myarray[$index]['value']['rating'] == 5-$i && $myarray[$index]['value']['rating_count'] > 0) {
                echo $myarray[$index]['value']['rating_count'];
            } else {
                echo 0;
            }
        ?>
    </li>
    <?php } ?>