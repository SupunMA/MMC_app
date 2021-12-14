<h5>
    <b>අද දිනය -
        <?php
        $dt = new DateTime();
        echo $dt->format('Y-m-d | වේලාව - g:i');
        ?>
    </b> 
    
</h5>

<h5>
    සෑම මසකම<b> 
        <?php
    //get Due date from loan table
        $date = Carbon\Carbon::createFromFormat('Y-m-d', $item->loanDate);
        echo $dueDate = $date->format('d');
        ?>
    </b>දින හෝ ඊට පෙර මුදල් ගෙවිය යුතුයි.
</h5>