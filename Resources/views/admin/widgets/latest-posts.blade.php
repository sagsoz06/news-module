<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('news::post.latest posts') }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tbody><tr>
                <th style="width: 10px">#</th>
                <th>{{ trans('news::post.table.title') }}</th>
                <th>{{ trans('news::post.table.slug') }}</th>
            </tr>
            <?php if (isset($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>
                            {{ $post->slug }}
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
