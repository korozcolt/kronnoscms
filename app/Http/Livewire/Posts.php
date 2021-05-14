<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;

class Posts extends Component
{
    use WithPagination;

    public $slug;
    public $title;
    public $categories;
    public $tags;
    public $content;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public function rules(){ 
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('posts','slug')->ignore($this->modelId)],
            'content' => 'required',
            'tags' => 'required',
            'categories' => 'required',            
        ];
    }

    public function updatedTitle($value){
        $this->slug = Str::slug($value);
    }
    
    /**
     * Function create a page
     *
     * @return void
     */
    public function create(){ 
        $this->validate();
        Post::create($this->modelData());
        $this->modalFormVisible = false;
        $this->cleanVars();
    }
    
    /**
     * update
     *
     * @return void
     */
    public function update(){
        $this->validate();
        Post::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
        $this->resetValidation();
    }
    
    /**
     * Delete a page
     *
     * @return void
     */
    public function delete(){
        Post::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }
    
    /**
     * Paginate 5 pages in the current page
     *
     * @return void
     */
    public function read(){
        return Post::paginate(5);
    }

    public function loadModel(){
        $data = Post::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
        $this->categories = $data->category_id;
        $this->tags = $data->tag_id;
     }

    public function mount(){ 
        $this->resetPage();
        $this->categories = Category::all();
        $this->tags = Tag::all();
    }

    public function updateShowModal($id){
        $this->resetValidation();
        $this->cleanVars();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }
    
    /**
     * Shows the form modal
     * of the create function
     *
     * @return void
     */
    public function createShowModal(){ 

        $this->resetValidation();
        $this->cleanVars();
        $this->modalFormVisible = true;
    }
    
    /**
     * The data for the model mapped to
     * in this function
     *
     * @return void
     */
    public function modelData(){
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'category_id' => $this->categories,
            'tag_id' => $this->tags,
        ];
    }
    
    /**
     * Clean Variables and reset all to null
     *
     * @return void
     */
    public function cleanVars(){ 
        $this->modelId = null;
        $this->title = null;
        $this->slug = null;
        $this->content = null;
        $this->categories = null;
        $this->tags = null;
    }
        
    /**
     * The livewire render function
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.posts',[
            'data' => $this->read(),
        ]);
    }
}
