@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="row" v-if="user">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="twitch_user_search" placeholder="Search user by name"
                           v-model="searchBox">
                </div>
                <div>
                    <button class="btn btn-success" @click.prevent="getTwitchUsersByName">
                        Search User
                    </button>
                    <button class="btn btn-danger" @click.prevent="getTwitchUsers"
                            v-if="onSearch == true">Cancel
                    </button>
                </div>
            </div>
            <div v-for="twitchUser in twitchUsers">
                <div class="card" style="border: 1px solid #ababab;border-radius: 5px">
                    <div class="card-header bg-transparent"><strong>@{{ twitchUser.display_name }}</strong></div>
                    <div class="card-body">
                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-2">
                                    <img :src="twitchUser.profile_image_url" alt="..." width="50px"
                                         height="50px">

                                </div>
                                <div class="col-md-6 ">
                                    <p>@{{twitchUser.description}}</p>
                                </div>
                                <div class="col-md-4">
                                    <div v-if="twitchUser.followed && !twitchUser.subscribed">
                                        <a class="btn btn-warning" @click.prevent="subscribeToUser(twitchUser)">
                                            Subscribe
                                        </a>
                                    </div>
                                    <div v-else-if="twitchUser.id != user.twitch_id">
                                        <a class="btn btn-primary" @click.prevent="followUser(twitchUser)">
                                            Follow ❤️
                                        </a>
                                    </div>
                                    <div v-if="twitchUser.followed">
                                        <a class="btn btn-info" @click.prevent="selectUser(twitchUser)">
                                            Select
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <p class="text-info" v-if="twitchUser.followed">
                            You follow this user
                        </p>
                        <p class="text-success" v-if="twitchUser.id == user.twitch_id">
                            This is you
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h5 v-if="selectedUserChannel != ''">User @{{selectedUserChannel}}</h5>
            <div v-for="notification in notifications">
                <div>@{{ notification.body }}</div>
            </div>
            <!-- Add a placeholder for the Twitch embed -->
            <div id="twitch-embed"></div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                twitchUsers: {},
                searchBox: '',
                onSearch: false,
                selectedUserChannel: '',
                notifications: {},
                user: {!! Auth::check() ? Auth::user()->toJson() : null !!}
            },
            mounted() {
                this.twitchUsers = {};
                this.getTwitchUsers();
                this.listen();
            },
            methods: {
                getTwitchUsers(){
                    searchBox = '';
                    axios.get('/api/twitch/users')
                        .then((response) => {
                            this.twitchUsers = response.data;
                            this.onSearch = false;
                        })
                        .catch(function (error) {
                            console.log(error)
                        })
                },
                subscribeToUser(twitchUser){
                    axios.post('/api/twitch/subscribe', {
                        twitch_user_id: twitchUser.id
                    })
                        .then((response) => {
                            twitchUser.subscribed = true;
                        })
                        .catch(function (error) {
                            console.log(error)
                        })
                },
                followUser(twitchUser){
                    axios.post('/api/twitch/follow', {
                        twitch_user_id: twitchUser.id
                    })
                        .then((response) => {
                            twitchUser.followed = true;
                        })
                        .catch(function (error) {
                            console.log(error)
                        })
                },
                selectUser(twitchUser){
                    this.selectedUserChannel = twitchUser.display_name;
                    twitchEmbeded = new Twitch.Embed("twitch-embed", {
                        width: 854,
                        height: 480,
                        channel: this.selectedUserChannel
                    });
                },
                getTwitchUsersByName(){
                    axios.get('/api/twitch/users/name', {
                        params: {
                            twitch_user_search: this.searchBox
                        }
                    })
                        .then((response) => {
                            this.twitchUsers = {};
                            this.twitchUsers = response.data;
                            this.onSearch = true;
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                },
                listen() {
                    Echo.private('user-notify')
                        .listen('NewNotification', ($notification) => {
                            this.notifications.unshift($notification);
                        });
                }
            }
        });
    </script>
    <script src="https://embed.twitch.tv/embed/v1.js"></script>
@endsection
