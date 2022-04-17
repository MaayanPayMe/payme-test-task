@extends('layout.app')

@section('content')
    <h1>New Sale Creation</h1>

    <table>
        <form method="POST" action="/sales">
            @csrf
            <tr>
                <td>
                    <label for="productName">Product name</label>
                </td>
                <td>
                    <input type="text" id="productName" name="productName" placeholder="Enter product name" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="price">Price</label>
                </td>
                <td>
                    <input type="number" id="price" name="price" placeholder="Enter price">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="currency">Currency</label>
                </td>
                <td>
                    <select id="currency" name="currency">
                        <option value="ils">ILS</option>
                        <option value="usd">USD</option>
                        <option value="eur">EUR</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                    <input type="submit" name="submit" value="Insert payment details"/>
                </td>
            </tr>
        </form>
    </table>

{{--    @if ($errors->any())--}}
{{--        <div class="alert alert-danger">--}}
{{--            <ul>--}}
{{--                @foreach ($errors->all() as $error)--}}
{{--                    <li>{{ $error }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}
@show
