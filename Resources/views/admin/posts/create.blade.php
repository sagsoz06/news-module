@extends('layouts.master')

@section('styles')
@stop

@section('content-header')
<h1>
    {{ trans('news::post.title.create post') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('admin.news.post.index') }}">{{ trans('news::post.title.post') }}</a></li>
    <li class="active">{{ trans('news::post.title.create post') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.news.post.store'], 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-10">
        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers', ['fields' => ['title', 'slug']])
            <div class="tab-content">
                <?php $i = 0; ?>
                <?php foreach (LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                    <?php $i++; ?>
                    <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                        @include('news::admin.posts.partials.create-fields', ['lang' => $locale])
                    </div>
                <?php endforeach; ?>
                <?php if (config('asgard.news.config.post.partials.normal.create') !== []): ?>
                    <?php foreach (config('asgard.news.config.post.partials.normal.create') as $partial): ?>
                    @include($partial)
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('news::post.button.create post') }}</button>
                    <button class="btn btn-default btn-flat" name="button" type="reset">{{ trans('core::core.button.reset') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.news.post.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div> {{-- end nav-tabs-custom --}}
    </div>
    <div class="col-md-2">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label("category", trans('news::category.title.category').':') !!}
                    <select name="category_id" id="category" class="form-control{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <?php foreach ($categories as $category): ?>
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                        <?php endforeach; ?>
                    </select>
                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                    {!! Form::label("status", trans('news::post.form.status').':') !!}
                    <select name="status" id="status" class="form-control">
                        <?php foreach ($statuses as $id => $status): ?>
                        <option value="{{ $id }}" {{ old('status', 0) == $id ? 'selected' : '' }}>{{ $status }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                @tags('asgardcms/news')
                <hr/>
                @mediaMultiple('newsImage', null, null, trans('news::post.form.thumbnail'))
            </div>
        </div>
        @if(Authentication::hasAccess(['blog.posts.sitemap']))
        <div class="box box-primary">
            <div class="box-body">
                <div class='form-group{{ $errors->has("meta_robot_no_index") ? ' has-error' : '' }}'>
                    {!! Form::checkbox("meta_robot_no_index", 'noindex', old("meta_robot_no_index"), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_index", trans('news::post.form.meta_robot_no_index')) !!}
                    {!! $errors->first("meta_robot_no_index", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::checkbox("meta_robot_no_follow", 'nofollow', old("meta_robot_no_follow"), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_follow", trans('news::post.form.meta_robot_no_follow')) !!}
                    {!! $errors->first("meta_robot_no_follow", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::checkbox("sitemap_include", '1', old("sitemap_include", 1), ['class' => 'flat-blue']) !!}
                    {!! Form::label("sitemap_include", trans('core::sitemap.title.include')) !!}
                    {!! $errors->first("sitemap_include", '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_frequency', trans('core::sitemap.title.frequency'), $errors, $sitemapFrequencies, 'weekly') !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_priority', trans('core::sitemap.title.priority'), $errors, $sitemapPriorities, '0.9') !!}
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
