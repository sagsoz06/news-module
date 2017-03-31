@extends('layouts.master')

@section('styles')
@stop

@section('content-header')
<h1>
    {{ trans('news::post.title.edit post') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('admin.news.post.index') }}">{{ trans('news::post.title.post') }}</a></li>
    <li class="active">{{ trans('news::post.title.edit post') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.news.post.update', $post->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-10">
        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers', ['fields' => ['title', 'slug']])
            <div class="tab-content">
                <?php $i = 0; ?>
                <?php foreach (LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                    <?php $i++; ?>
                    <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                        @include('news::admin.posts.partials.edit-fields', ['lang' => $locale])
                    </div>
                <?php endforeach; ?>
                <?php if (config('asgard.news.config.post.partials.normal.edit') !== []): ?>
                    <?php foreach (config('asgard.news.config.post.partials.normal.edit') as $partial): ?>
                        @include($partial)
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <button class="btn btn-default btn-flat" name="button" type="reset">{{ trans('core::core.button.reset') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.news.post.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div> {{-- end nav-tabs-custom --}}
    </div>
    <div class="col-md-2">
        <div class="box box-primary">
            <div class="box-body">

                @if(Authentication::hasAccess(['news.posts.author']))
                {!! Form::normalSelect('user_id', trans('news::post.form.user_id'), $errors, $userLists, isset($post->author->id) ? $post->author->id : null) !!}
                @endif
                <div class="form-group">
                    {!! Form::label("category", trans('news::category.title.category').':') !!}
                    <select name="category_id" id="category" class="form-control">
                        <?php foreach ($categories as $category): ?>
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    {!! Form::label("status", trans('news::post.form.status').':') !!}
                    <select name="status" id="status" class="form-control">
                        <?php foreach ($statuses as $id => $status): ?>
                        <option value="{{ $id }}" {{ old('status', $post->status) == $id ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-group{{ $errors->has("created_at") ? ' has-error' : '' }}">
                        {!! Form::label("created_at", trans('news::post.form.created_at').':') !!}
                        <div class='input-group date' id='created_at'>
                            <input type='text' class="form-control" name="created_at" value="{{ old('created_at', $post->created_at->format('d.m.Y H:i')) }}" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        {!! $errors->first("created_at", '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                @tags('asgardcms/news', $post)
                @mediaMultiple('newsImage', $post, null, trans('news::post.form.thumbnail'))
            </div>
        </div>
        @if(Authentication::hasAccess(['news.posts.sitemap']))
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    {!! Form::hidden("meta_robot_no_index", 'index') !!}
                    {!! Form::checkbox("meta_robot_no_index", 'noindex', old("meta_robot_no_index", ($post->meta_robot_no_index == 'index' ? 0 : 1)), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_index", trans('news::post.form.meta_robot_no_index')) !!}
                    {!! $errors->first("meta_robot_no_index", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::hidden("meta_robot_no_follow", 'follow') !!}
                    {!! Form::checkbox("meta_robot_no_follow", 'nofollow', old("meta_robot_no_follow", ($post->meta_robot_no_follow == 'follow' ? 0 : 1)), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_follow", trans('news::post.form.meta_robot_no_follow')) !!}
                    {!! $errors->first("meta_robot_no_follow", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::hidden("sitemap_include", 0) !!}
                    {!! Form::checkbox("sitemap_include", 1, old("sitemap_include", ($post->sitemap_include == 1 ? 1 : 0)), ['class' => 'flat-blue']) !!}
                    {!! Form::label("sitemap_include", trans('core::sitemap.title.include')) !!}
                    {!! $errors->first("sitemap_include", '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_frequency', trans('core::sitemap.title.frequency'), $errors, $sitemapFrequencies, $post->sitemap_frequency) !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_priority', trans('core::sitemap.title.priority'), $errors, $sitemapPriorities, $post->sitemap_priority) !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index', ['name' => 'posts']) }}</dd>
    </dl>
@stop

@section('scripts')
<script src="{{ Module::asset('news:js/MySelectize.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        //CKEDITOR.replaceAll(function( textarea, config ) {
//            console.log(textarea);
//            config.language = '<?= App::getLocale() ?>';
//        } );
    });
    $( document ).ready(function() {
        $(document).keypressAction({
            actions: [
                { key: 'b', route: "<?= route('admin.news.post.index') ?>" }
            ]
        });
        $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
        $(".textarea").wysihtml5();
        //Date picker
        $('#created_at').datetimepicker({
            locale: '<?= App::getLocale() ?>',
            allowInputToggle: true,
            format: 'DD.MM.YYYY HH:mm'
        });
    });
</script>
@stop
