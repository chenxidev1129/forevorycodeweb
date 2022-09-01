<?php

namespace App\Repositories;

use Exception;

class DefaultProfileRepository{

    /**
     * Function used to get default profile data.
     * @return array()
     * @throws Throwable $th
     */
    public static function getDefaultProfileData($request)
    {
        try {
                return array(
                'title' => 'Ralph’s Journey',
                'journey' => "Our beloved Ralph Sarris, age 70, resident of Austin, was born into Eternal Life on Thursday, October 29, 2020. He is reunited with his parents, Raymond and Sally Gomez Sarris; his brother, Donald Sarris his sister, Roseanna Sarris. Ralph is survived by his son, grandsons, and grandaugthers.

                Ralph was born in Brooklyn, New York, to Greek immigrant parents, Themis (née Katavolos) and George Andrew Sarris, and grew up in Ozone Park, Queens.[2] After attending John Adams High School in South Ozone Park (where he overlapped with Jimmy Breslin), he graduated from Columbia University in 1951 and then served for three years in the Army Signal Corps before moving to Paris for a year, where he befriended Jean-Luc Godard and François Truffaut. Upon returning to New York's Lower East Side, Sarris briefly pursued graduate studies at his alma mater and Teachers College, Columbia University before turning to film criticism as a vocation.",
                'profile_image' => 'assets/images/view-profile/ralph.png',
                'cover_image' => 'assets/images/view-profile/profile-banner.jpg',
                'image' => [
                        [
                            'media' => 'assets/images/view-profile/photo01-lg.jpg',
                            'title' => 'Ralph “Raphy” Sarris - Photos',
                            'caption' => 'Dad’s Dad (Daniel Sarris) and his General James Smith sending letters via carrier pigeon.',
                            'position' => '1',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'media' => 'assets/images/view-profile/photo02-lg.jpg',
                            'title' => 'Ralph “Raphy” Sarris - Photos',
                            'caption' => 'Trip to Myrtle Beach with the family in 1977',
                            'position' => '2',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'media' => 'assets/images/view-profile/photo03-lg.jpg',
                            'title' => 'Ralph “Raphy” Sarris - Photos',
                            'caption' => 'Vacation at Myrtle Beach with Dad and Mom August 1973.',
                            'position' => '3',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'media' => 'assets/images/view-profile/photo04.jpg',
                            'title' => '',
                            'caption' => '',
                            'position' => '4',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'media' => 'assets/images/view-profile/photo05.jpg',
                            'title' => '',
                            'caption' => 'Trip to Myrtle Beach with the family in 1977',
                            'position' => '5',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                    ],
                'video' =>[
                        [
                            'title' => 'Ralph “Raphy” Sarris - Videos',
                            'thumbnail' => 'assets/images/view-profile/photo01.jpg',
                            'media' => 'assets/videos/view-profile/demo.mp4',
                            'caption' => 'Vacation at Myrtle Beach with Dad and Mom August 1973',
                            'position' => '1',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'title' => 'Ralph “Raphy” Sarris - Videos',
                            'thumbnail' => 'assets/images/view-profile/photo02.jpg',
                            'media' => 'assets/videos/view-profile/army.mp4',
                            'caption' => 'Trip to Myrtle Beach with the family in 1977',
                            'position' => '2',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'title' => 'Ralph “Raphy” Sarris - Videos',
                            'thumbnail' => 'assets/images/view-profile/photo03.jpg',
                            'media' => '',
                            'caption' => '',
                            'position' => '3',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'title' => 'Ralph “Raphy” Sarris - Videos',
                            'thumbnail' => 'assets/images/view-profile/photo04.jpg',
                            'media' => '',
                            'caption' => '',
                            'position' => '4',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'title' => 'Ralph “Raphy” Sarris - Videos',
                            'thumbnail' => 'assets/images/view-profile/photo05.jpg',
                            'media' => '',
                            'caption' => '',
                            'position' => '5',
                            'created_at' => '2020-11-11 04:54:51',
                        ]
                    ],
                    'voice_note' => [
                        [
                            'thumbnail' => 'assets/images/user-default.jpg',
                            'media' => 'assets/images/view-profile/demo.mp3',
                            'caption' => 'Talking to Angels - Sarah Sarris',
                            'duration' => '1:47',
                            'position' => '1',
                            'created_at' => '2020-11-14 04:54:51',
                        ],
                        [
                            'thumbnail' => 'assets/images/user-default.jpg',
                            'media' => 'assets/images/view-profile/demo.mp3',
                            'caption' => 'Talking to Angels - Sarah Sarris',
                            'duration' => '1:47',
                            'position' => '2',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'thumbnail' => 'assets/images/user-default.jpg',
                            'media' => 'assets/images/view-profile/demo.mp3',
                            'caption' => 'Talking to Angels - Sarah Sarris',
                            'duration' => '1:47',
                            'position' => '3',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                        [
                            'thumbnail' => 'assets/images/user-default.jpg',
                            'media' => 'assets/images/view-profile/demo.mp3',
                            'caption' => 'Talking to Angels - Sarah Sarris',
                            'duration' => '1:47',
                            'position' => '4',
                            'created_at' => '2020-11-11 04:54:51',
                        ],
                    ],
                    'stories_articles' => [
                        [
                            'author' => 'Christine Sarris',
                            'media' => 'assets/images/view-profile/photo01.jpg',
                            'title' => 'Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris',
                            'text' => "<p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
                            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
                            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>",
                            'article_image' => 'assets/images/view-profile/article01.jpg',
                            'position' => '1',
                            'created_at' => '2020-09-05 04:54:51',
                        ],
                        [
                            'author' => 'Christine Sarris',
                            'media' => 'assets/images/view-profile/photo01.jpg"',
                            'title' => 'Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris',
                            'text' => "<p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
                            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
                            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>",
                            'article_image' => 'assets/images/view-profile/article01.jpg',
                            'position' => '2',
                            'created_at' => '2020-09-05 04:54:51',
                        ],
                        [
                            'author' => 'Christine Sarris',
                            'media' => 'assets/images/view-profile/photo02.jpg"',
                            'title' => 'Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris',
                            'text' => "<p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
                            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
                            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>",
                            'article_image' => 'assets/images/view-profile/article01.jpg',
                            'position' => '3',
                            'created_at' => '2020-09-05 04:54:51',
                        ],
                        [
                            'author' => 'Christine Sarris',
                            'media' => 'assets/images/view-profile/photo02.jpg"',
                            'title' => 'Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris',
                            'text' => "<p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
                            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
                            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>",
                            'article_image' => 'assets/images/view-profile/article01.jpg',
                            'position' => '4',
                            'created_at' => '2020-09-05 04:54:51',
                        ],
                        [
                            'author' => 'Christine Sarris',
                            'media' => 'assets/images/view-profile/photo02.jpg"',
                            'title' => 'Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris',
                            'text' => "<p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
                            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
                            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>",
                            'article_image' => 'assets/images/view-profile/article01.jpg',
                            'position' => '5',
                            'created_at' => '2020-09-05 04:54:51',
                        ]
                    ],
                    'gravesite_details' => [
                            'default_grave_image' => 'headstone.jpg',
                            'latitude' => '30.264630',
                            'longitude' => '-97.728030',
                            'location' => 'TX 78702 Gravesite Location Row 5 Plot 7 Cordoza Road, 909 Navasota St, Texas State Cemetery, Austin',
                            'gravesite_prayers' => 'Catholic Prayer - English
                            Our Father, who art in heaven, hallowed be thy name; thy kingdom come, thy will be done, on earth as it is in heaven. Give us this day our daily bread and forgive us our trespasses, as we forgive those who trespass against us and lead us not into temptation, but deliver us from evil.
                            
                            Catholic Prayer - Hebrew
                            Our Father, who art in heaven, hallowed be thy name; thy kingdom come, thy will be done, on earth as it is in heaven. Give us this day our daily bread and forgive us our trespasses, as we forgive those who trespass against us and lead us not into temptation, but deliver us from evil.
                            
                            Catholic Prayer - Chinese
                            Our Father, who art in heaven, hallowed be thy name; thy kingdom come, thy will be done, on earth as it is in heaven. Give us this day our daily bread and forgive us our trespasses, as we forgive those who trespass against us and lead us not into temptation, but deliver us from evil.
                            
                            Catholic Prayer
                            Our Father, who art in heaven, hallowed be thy name; thy kingdom come, thy will be done, on earth as it is in heaven. Give us this day our daily bread and forgive us our trespasses, as we forgive those who trespass against us and lead us not into temptation, but deliver us from evil.
                            
                            Catholic Prayer
                            Our Father, who art in heaven, hallowed be thy name; thy kingdom come, thy will be done, on earth as it is in heaven. Give us this day our daily bread and forgive us our trespasses, as we forgive those who trespass against us and lead us not into temptation, but deliver us from evil.',
                    ],

                );
        } catch (\Throwable $th) {
            throw $th;
        }
    }



}
