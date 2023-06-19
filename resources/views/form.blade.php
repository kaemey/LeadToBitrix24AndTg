@extends('layouts.app')
 

@section('content')
<div class="w-25 text-center">
<form method="post" action="{{ route('form.store') }}" class="m-5">
@csrf
                    <div class="form-group">
                        <label for="form-control">Введите ФИО</label>
                        <input type="text" name="name" class="form-control" placeholder="Введите ФИО">
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputPassword1">Ваша дата рождения</label><br>
                        <input type="text" name="date" class="flat" placeholder="Введите дату">
                    </div>

                    <div class="form-group">
                        <label for="form-control">Введите телефон</label>
                        <input type="text" name="phone" class="form-control" placeholder="Введите телефон">
                    </div>

                    <div class="form-group">
                        <label for="form-control">Введите email</label>
                        <input type="email" name="email" class="form-control" placeholder="Внесите email">
                    </div>

                    <div class="form-group">
                        <label for="form-control">Ваш комментарий</label>
                        <input type="text" name="comment" class="form-control" placeholder="Введите комментарий">
                    </div>

                    <button type="submit" class="btn btn-primary m-2">Отправить</button>
                </form>
</div>


@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>flatpickr(".flat", {});</script>
@endsection