@extends('_layouts.app')

@section('title', 'Main Panel')

@section('content')

    {{--    **Правила отображения /main в зависимости от User (его ролей, участия в Companies):**--}}

    {{--    - User - участник только одной Company с ролью user -> переадресация на список Posts в этой компании с правами простого User.--}}
    {{--    - User - участник только одной Company с ролью company_user -> отображение двух боксов-ссылок на страницы Users (перечень user в этой Company) and Posts (перечень Posts в этой Company).--}}
    {{--    - User - участник нескольких Company -> отображение трех боксов-ссылок на страницы Companies, Users и Posts (где User имеет роли company_head, user).--}}

    <section>

        <h1>User Panel</h1>

        <div class="admin-panel-content-box-wrapper">

            <a href="{{ route('') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.user/>
                    <h2>Users</h2>
                    <p></p>
                </div>
            </a>

            <a href="{{ route('') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.company/>
                    <h2>Companies</h2>
                    <p></p>
                </div>
            </a>

            <a href="{{ route('') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.post/>
                    <h2>Posts</h2>
                    <p></p>
                </div>
            </a>

        </div>

    </section>

@endsection
