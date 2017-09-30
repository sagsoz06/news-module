<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("$lang.title") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[title]", trans('news::post.form.title')) !!}
            {!! Form::text("{$lang}[title]", old("$lang.title"), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('news::post.form.title')]) !!}
            {!! $errors->first("$lang.title", '<span class="help-block">:message</span>') !!}
        </div>
        <div class='form-group{{ $errors->has("$lang.slug") ? ' has-error' : '' }}'>
           {!! Form::label("{$lang}[slug]", trans('news::post.form.slug')) !!}
           {!! Form::text("{$lang}[slug]", old("$lang.slug"), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('news::post.form.slug')]) !!}
           {!! $errors->first("$lang.slug", '<span class="help-block">:message</span>') !!}
        </div>

        <div class='form-group{{ $errors->has("$lang.intro") ? ' has-error' : '' }}'>
            <textarea class="textarea" name="{{$lang}}[intro]" placeholder="{{ trans('blog::post.form.intro') }}" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                {!! old("{$lang}.intro") !!}
            </textarea>
            {!! $errors->first("$lang.intro", '<span class="help-block">:message</span>') !!}
        </div>

        @editor('content', trans('news::post.form.content'), old("{$lang}.content"), $lang)

        <?php if (config('asgard.news.config.post.partials.translatable.create') !== []): ?>
            <?php foreach (config('asgard.news.config.post.partials.translatable.create') as $partial): ?>
            @include($partial)
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo-{{$lang}}">
                        {{ trans('news::post.form.meta_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseTwo-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    {!! Form::i18nInput("meta_title", trans('news::post.form.meta_title'), $errors, $lang) !!}

                    {!! Form::i18nTextarea("meta_description", trans('news::post.form.meta_description'), $errors, $lang, null, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFacebook-{{$lang}}">
                        {{ trans('news::post.form.facebook_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseFacebook-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    {!! Form::i18nInput("og_title", trans('news::post.form.og_title'), $errors, $lang) !!}

                    {!! Form::i18nInput("og_description", trans('news::post.form.og_description'), $errors, $lang) !!}

                    {!! Form::i18nSelect("og_type", trans('news::post.form.og_type'), $errors, $lang, $ogTypes) !!}

                </div>
            </div>
        </div>
    </div>
</div>
