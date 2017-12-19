<reply route="{{ route('replies.update', $reply) }}" :attributes="{{ $reply }}" inline-template>
    <div class="panel panel-default" id="reply-{{ $reply->id }}">
        <div class="level panel-heading">
            <h5 class="flex">
                By <a href="{{ route('profile.show', $reply->owner) }}">
                        {{ $reply->owner->name }}
                    </a>
                {{ $reply->created_at->diffForHumans() }}
            </h5>

            <div>
                <favorite
                        route="{{ route('favorites.store', $reply) }}"
                        :reply="{{ $reply }}">
                </favorite>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="isEditing" v-cloak>
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        <button @click="isEditing = false" class="btn btn-link btn-xs">Cancel</button>
                    </div>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        @can('update', $reply)
            <div class="panel-footer">
                <div class="level">
                    <div>
                        <button @click="isEditing = true" class="btn btn-default btn-xs mr-10">Edit</button>
                    </div>
                    <div>
                        <button @click="destroy" class="btn btn-danger btn-xs">Delete</button>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</reply>
