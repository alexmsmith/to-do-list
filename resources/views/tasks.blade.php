@extends('layouts.app')

@section('content')
    <div class="task">
        <div class="task__input">
            <input
                type="text"
                placeholder="Insert task name"
                id="task-input"
            />
        </div>
        <button class="task__btn" id="add-task-btn">Add</button>
    </div>
    
    @include('partials.table')
@endsection