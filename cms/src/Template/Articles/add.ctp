<!-- File: src/Template/Articles/add.ctp -->

<h1>Add Article</h1>
<?php
    echo $this->Form->create($article);
    // Hard code the user for now.

    /* control() is used to create form elements of same name
        it will output different form elements based on the model field specified
        uses inflection to generate a label text */
    echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => 1]);
    echo $this->Form->control('title');
    echo $this->Form->control('body', ['rows' => '3']);
    echo $this->Form->button(__('Save Article'));
    echo $this->Form->control('tags._string', ['type' => 'text']);
    echo $this->Form->end(); //this closes the form
?>