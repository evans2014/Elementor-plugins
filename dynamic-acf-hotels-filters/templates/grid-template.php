<div class="daf-container">
<div class="dat-filter">
        <form class="daf-filters">
        <?php

        $posts = get_posts([
            'post_type' => 'places',
            'posts_per_page' => 1
        ]);

        if(!empty($posts)){
            $acf_fields = get_field_objects($posts[0]->ID);
        }


        if($acf_fields){
            foreach($acf_fields as $field){

                if($field['type']=='select'||$field['type']=='checkbox'){
                    echo '<select name="'.$field['name'].'">';
                    echo '<option value="">All '.$field['label'].'</option>';
                    foreach($field['choices'] as $value=>$label){ echo '<option value="'.$value.'">'.$label.'</option>'; }
                    echo '</select>';
                } elseif($field['type']=='text'){
                    echo '<input type="text" name="'.$field['name'].'" placeholder="'.$field['label'].'">';
                }
            }
        }
        ?>
        </form>
</div>

            <div id="daf-grid" class="daf-grid"></div>

            <div id="daf-map" class="daf-map"></div>
</div>


