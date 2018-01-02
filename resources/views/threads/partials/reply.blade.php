<reply route="{{ route('replies.update', $reply) }}" best="{{ $best }}" :attributes="{{ $reply }}" inline-template>
    <div :class="panel" id="reply-{{ $reply->id }}">
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

            <div v-else class="level">
                <div class="flex">@{{ body }}</div>

                @can('update', $reply->thread)
                    <div v-if="isBest">
                        <a @click.prevent="unMarkAsBest('{{ route('best.reply.destroy', $reply) }}')"
                           href="/"
                           :class="icon"
                           title="Remove best reply badge">
                            <h1 class="glyphicon glyphicon-ok"></h1>
                        </a>
                    </div>

                    <div v-else v-cloak>
                        <a @click.prevent="markAsBest('{{ route('best.reply.store', $reply) }}')"
                           href="/"
                           :class="icon"
                           title="Mark as best">
                            <h1 class="glyphicon glyphicon-ok"></h1>
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        @can('update', $reply)
            <div class="panel-footer">
                <div class="level">
                    <div>
                        <button @click="isEditing = true"
                                class="btn btn-default btn-xs mr-10"
                        >Edit
                        </button>
                    </div>

                    <div>
                        <button @click="destroy" class="btn btn-danger btn-xs">Delete</button>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</reply>
