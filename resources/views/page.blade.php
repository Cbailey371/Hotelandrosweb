@extends('layouts.app')

@section('title', $page->name ?? 'Home')

@section('content')
    @if(isset($page->content['sections']))
        @foreach($page->content['sections'] as $section)
            @if(isset($section['settings']['visible']) && $section['settings']['visible'])
                <x-dynamic-component :component="'sections.' . $section['type']" :data="$section['data']" :rooms="$rooms ?? []"
                    :carouselImages="$carouselImages ?? []" :attractions="$attractions ?? []" mode="public" />
            @endif
        @endforeach
    @endif
@endsection