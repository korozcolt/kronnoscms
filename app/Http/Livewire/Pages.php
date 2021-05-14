<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Page;

class Pages extends Component
{
    use WithPagination;

    public $slug;
    public $title;
    public $content;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public function rules(){ 
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages','slug')->ignore($this->modelId)],
            'content' => 'required',
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
        Page::create($this->modelData());
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
        Page::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
        $this->resetValidation();
    }
    
    /**
     * Delete a page
     *
     * @return void
     */
    public function delete(){
        Page::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }
    
    /**
     * Paginate 5 pages in the current page
     *
     * @return void
     */
    public function read(){
        return Page::paginate(5);
    }

    public function loadModel(){
        $data = Page::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
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
    }
        
    /**
     * The livewire render function
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.pages',[
            'data' => $this->read(),
        ]);
    }
}
