@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="task">
            <div class="task__input">
                <input
                    type="text"
                    placeholder="Insert task name"
                />
            </div>
            <div class="task__btn">
                <button>Add</button>
            </div>
        </div>
        
        @include('partials.table')
    </div>
@endsection