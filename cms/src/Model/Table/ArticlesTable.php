<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

//this creates the text class
use Cake\Utility\Text;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'joinTable' => 'articles_tags',
            'dependent' => true
        ]); // this allows associtation of a tag

    }

    //adding a simple slug generator
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->tag_string) 
        {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }
        
        if ($entity->isNew() && !$entity->slug) 
        {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    //this is added to edit saved articles
    public function edit($slug)
    {
        $article = $this->Articles
        ->findBySlug($slug)
        ->contain(['Tags']) //load the associated tags
        ->firstOrFail();
        if ($this->request->is(['post', 'put'])) 
        { //patch entry allows us to update the data
                $this->Articles->patchEntity($article, $this->request->getData());
                if ($this->Articles->save($article)) 
                { // this allows to save the update
                    $this->Flash->success(__('Your article has been updated.'));
                    return $this->redirect(['action' => 'index']);
                }
            $this->Flash->error(__('Unable to update your article.'));
        }
        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');

        // Set tags to the view context
        $this->set('tags', $tags);


        $this->set('article', $article);
    }

    protected function _buildTags($tagString)
    {
        // Trim tags
        $newTags = array_map('trim', explode(',', $tagString));
        // Remove all empty tags
        $newTags = array_filter($newTags);
        // Reduce duplicated tags
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags]);

        // Remove existing tags from the list of new tags.
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // Add existing tags.
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // Add new tags.
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }
        return $out;
    }

    public function view($slug = null)
    {
        // contain() tells ArticleTable object to populate the Tags association
        // when the article is loaded
        $article = $this->Articles->findBySlug($slug)->contain(['Tags'])
            ->firstOrFail();
        $this->set(compact('article'));
    }


  


}