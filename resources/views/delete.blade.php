@extends("layouts.app")
@section("page-title", "Delete")

@section("content")
@if($status == 200)
    @include("layouts.success", [
    "color" => "text-green-500",
    "status" => "Your upload has been successfully deleted."
])
@elseif($status == 404)
    @include("layouts.error", [
    "color" => "text-red-500",
    "status" => "The upload you are trying to delete does not exist."
])
@elseif($status == 403) {
    @include("layouts.error", [
    "color" => "text-red-500",
    "status" => "You are not authorized to delete this upload."
])
@elseif($status == 400) {
    @include("layouts.error", [
    "color" => "text-red-500",
    "status" => "The request you made was invalid."
])
@endif
@endsection
