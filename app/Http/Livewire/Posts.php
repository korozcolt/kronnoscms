<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;

class Posts extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $slug;
    public $title;
    public $author;
    public $category; 
    public $categories;
    public $tags;
    public $photo;
    public $tag;
    public $content;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public function rules(){ 
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('posts','slug')->ignore($this->modelId)],
            'content' => 'required',
            'tag_id' => 'required',
            'category_id' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048', 
            'author' => 'required'           
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
        $this->photo->store('photo','public');
        Post::create($this->modelData());
        $this->modalFormVisible = false;
        session()->flash('message', 'Datos guardados.');
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
        $this->category = $data->category_id;
        $this->tag = $data->tag_id;
        $this->author = $data->author;
     }

    public function mount(){ 
        $this->resetPage();
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
            'category_id' => $this->category_id,
            'tag_id' => $this->tag_id,
            'photo' => $this->photo,
            'author' => $this->author,
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
        $this->category_id = null;
        $this->tag_id = null;
        $this->photo = null;
        $this->author = null;
    }
        
    /**
     * The livewire render function
     *
     * @return void
     */
    public function render(){
        $this->categories = Category::orderBy('name')->get();
        $this->tags = Tag::orderBy('name')->get();
        return view('livewire.posts',[
            'data' => $this->read(),
        ]);
    }
}
