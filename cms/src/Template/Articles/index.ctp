<!-- File: src/Template/Articles/index.ctp -->

<h1>Articles</h1>
<p>
    <?= $this->Html->link('Add Article', ['action' => 'add']) ?>
</p>
<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($articles as $article): ?>
    <tr>
        <td>
        <!-- this -> Html is an instance of the CakePHP HtmlHelper 
            the link() method generates a HTML link with given link text (the first parameter)
            & URL (the second parameter)
            -->
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?>
            <!-- this is to add the delete link-->
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $article->slug],
                ['confirm' => 'Are you sure?'])
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>