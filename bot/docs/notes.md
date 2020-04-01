- [WS Events](#sec-1)
  - [Room Events](#sec-1-1)
    - [Send](#sec-1-1-1)
    - [Receive](#sec-1-1-2)
  - [Youtube Events](#sec-1-2)
    - [Send](#sec-1-2-1)
    - [Receive](#sec-1-2-2)
  - [Client Events](#sec-1-3)
    - [Receive](#sec-1-3-1)
  - [Self Events](#sec-1-4)
    - [Receive](#sec-1-4-1)
- [API](#sec-2)
  - [Youtube](#sec-2-1)
    - [Playlist](#sec-2-1-1)
    - [Search](#sec-2-1-2)
  - [Room](#sec-2-2)
    - [Users](#sec-2-2-1)
    - [Unread](#sec-2-2-2)
    - [Profile](#sec-2-2-3)
    - [Emoji](#sec-2-2-4)

### Some notes on IDs
In most if not all cases `_id` is the "user_list_id", that's the key used for sending ban messages so we might aswell call it that's

The `user_id` is the ID for account

`operator_id` This exists only if the user is a moderator, so we can do

```py
if User.operator_id:
    do_a_thing()
```
Side note: `assignedBy` is treated the same, but for oper. If it's empty, the user is not oper. (half star thing)

There is also a unique id for unbanning, retrieved from `42["room::operation::banlist",{"user_list_id":""}]`

AND youtube videos get an id which are unique per item in playlist, no matter if duplicated.

### More notes on things


If/when jumpin sorts out cloudflare, port [cfscrape](https://github.com/Anorov/cloudflare-scrape/blob/master/cfscrape/__init__.py) to aiohttp, maybe use pyjs to exec the javascript challenge instead of node.

Text formatting: \***bold**\* \__italic_\_
# WS Events<a id="sec-1"></a>

Initial connection is sending `2probe` and receiving `3probe` then sending `5`. Ping interval is sending `2` every 25 seconds; expecting `3` as response.

## Room Events<a id="sec-1-1"></a>

### Send<a id="sec-1-1-1"></a>

1.  `room::getIgnoreList`

    Response: `room::updateIgnore`
    
    ```json
    42[
      "room::getIgnoreList",
      {
        "roomName": "<ROOM>"
      }
    ]
    ```

2.  `room::handleChange`

    Response: `client::handleChange`, `room::handleChange`, `room::status`
    
    ```json
    42[
      "room::handleChange",
      {
        "handle": "<NICK>"
      }
    ]
    ```

3.  `room::isStillJoined`

    Response: `client::stillConnected` Sent every 5 minutes
    
    ```json
    42[
      "room::isStillJoined",
      {
        "room": "<ROOM>"
      }
    ]
    ```

4.  `room::join`

    Generated from POST to session API
    
    Response: `room::updateUserList`, `self::join` (maybe?)
    
    ```json
    42[
      "room::join",
      {
        "room": "<ROOM>",
        "user": {
          "user_id": "<24 CHAR STR>", 
          "username": "<ACCOUNT>",
          "isAdmin": false,
          "isSiteMod": false,
          "isSupporter": false,
          "isGold": null,
          "userIcon": null,
          "settings": {
            "playYtVideos": false,
            "allowPrivateMessages": true,
            "pushNotificationsEnabled": false,
            "receiveUpdates": false,
            "receiveMessageNotifications": true,
            "darkTheme": true,
            "videoQuality": "VIDEO_240",
            "userIcon": null,
            "ignoreList": []
          },
          "videoQuality": {
            "id": "VIDEO_240",
            "label": "240p",
            "dimensions": {
              "width": 320,
              "height": 240
            },
            "frameRate": 15,
            "bitRate": 128000
          }
        }
      }
    ]
    ```

5.  `room::message`

    Response: `room::message`
    
    ```json
    42[
      "room::message",
      {
        "message": "<MESSAGE>",
        "room": "<ROOM>"
      }
    ]
    ```

6.  `room::operation::closeBroadcast`

    Response: `room::status`, `room::updateUser`
    
    ```json
    42[
      "room::operation::closeBroadcast",
      {
        "user_list_id": "<USER'S ID>"
      }
    ]
    ```

7.  `room::operation::ban`

    Response: `room::status`, `room:userbanned`, `room::disconnect`
     
    Duration is in hours? "permanent" ban is `duration: "4464"`

    
    ```json
    42[
      "room::operation::ban",
      {
        "user_list_id": "<USER ID>",
        "duration": "1"
      }
    ]
    ```

8.  `room::operation::banlist`

    Response `client::banlist`
    
    ```json
    42[
      "room::operation::banlist",
      {
        "user_list_id": "<SELF ID>"
      }
    ]
    ```

9.  `room::operation::kick`

    Response: `room::status` , `room::disconnect`
    
    ```json
    42[
      "room::operation::kick",
      {
        "user_list_id": "<USER ID>"
      }
    ]
    ```

10. `room::operation::unban`

    Response: `room::status`, `client::banlist`
    
    ```json
    42[
      "room::operation::unban",
      {
        "banlistId": "<BAN ID FROM client::banlist>",
        "handle": "<HANDLE FROM client::banlist>"
      }
    ]
    ```

11. `room::setUserIsBroadcasting`

    Response: `room::updateUser`
    
    ```json
    42[
      "room::setUserIsBroadcasting",
      {
        "isBroadcasting": true
      }
    ]
    ```

12. `room::command`
    Some of the commands: `me`, `topic`, `shrug`, `clear`
    Responses depend on command sent. 
    ```json
    42[
      "room::command",
      {
        "message": {
        "command": "<COMMAND>",
        "value": "<MESSAGE>"
        },
      "room": "<ROOM>"
      }
    ] 
    ```

### Receive<a id="sec-1-1-2"></a>

1.  `room::updateUser`

    ```json
    42[
      "room::updateUser",
      {
        "user": {
          "_id": "",
          "handle": "",
          "operator_id": "",
          "user_id": "",
          "username": "",
          "isBroadcasting": true,
          "assignedBy": null,
          "isAdmin": false,
          "isSiteMod": false,
          "isSupporter": false,
          "userIcon": null,
          "color": "green"
        }
      }
    ]
    ```

2.  `room::updateIgnore`

    ```json
    42[
      "room::updateIgnore",
      {
        "ignoreList": []
      }
    ]
    ```

3.  `room::updateUserList`

    ```json
    42[
      "room::updateUserList",
      {
        "user": {
          "_id": "<ID?>",
          "handle": "addicted_profit",
          "operator_id": "<OP ID>",
          "user_id": "<USER ID>",
          "username": "aida",
          "isBroadcasting": false,
          "assignedBy": null,
          "isAdmin": false,
          "isSiteMod": false,
          "isSupporter": false,
          "userIcon": null,
          "color": "bluealt"
        }
      }
    ]
    ```

4.  `room::status`

    ```json
    42[
      "room::status",
      {
        "message": "<MSG>",
        "timestamp": "<ISO 8601 UTC>",
        "id": "<UUID4>"
      }
    ]
    ```
    
    Or?
    
    ```json
    42[
      "room::status",
      {
        "notification_type": "room",
        "message": "<MSG>",
        "timestamp": "<ISO 8601 UTC>",
        "id": "<UUID4>"
      }
    ]
    ```
    
    TODO: log `notification_type`'s

5.  `room::handleChange`

    ```json
    42[
      "room::handleChange",
      {
        "userId": "<USER ID>",
        "handle": "<NICK>"
      }
    ]
    ```

6.  `room::disconnect`

    ```json
    42[
      "room::disconnect",
      {
        "user": {
          "_id": "<USER ID>",
          "handle": "<NICK>",
          "operator_id": null,
          "user_id": null,
          "username": null,
          "isBroadcasting": false,
          "assignedBy": null,
          "isAdmin": false,
          "isSiteMod": false,
          "isSupporter": false,
          "userIcon": null,
          "color": "red"
        }
      }
    ]
    ```

7.  `room::message`

    ```json
    42[
      "room::message",
      {
        "handle": "<NICK>",
        "color": "redalt",
        "userId": "<USER ID>",
        "message": "<MESSAGE>",
        "timestamp": "<ISO 8601 UTC>",
        "id": "<UUID4>"
      }
    ]
    ```

## Youtube Events<a id="sec-1-2"></a>

### Send<a id="sec-1-2-1"></a>

1.  `youtube::checkisplaying`

    ```json
    42[
      "youtube::checkisplaying",
      {
        "notify": true
      }
    ]
    ```

2.  `youtube::play`

    ```json
    42[
      "youtube::play",
      {
        "videoId": "<YT ID>",
        "title": "<TITLE STR>"
      }
    ]
    ```

3.  `youtube::remove`

    id comes from API `/api/youtube/ROOM/playlist`
    
    ```json
    42[
      "youtube::remove",
      {
        "id": "<JUMPIN'S ASSIGNED ID>"
      }
    ]
    ```

### Receive<a id="sec-1-2-2"></a>

1.  `youtube::playlistUpdate`

    ```json
    42[
      "youtube::playlistUpdate",
      [
        {
          "startTime": null,
          "endTime": null,
          "description": null,
          "channelId": "<YT CHANNEL ID STR>",
          "pausedAt": null,
          "_id": "<JUMPIN'S VIDEO ID STR>",
          "mediaId": "<YT ID STR>",
          "title": "<YT TITLE STR>",
          "link": "<YT SHORT URL STR>",
          "duration": 240,
          "thumb": "<THUMBNAIL URL STR>",
          "mediaType": "TYPE_YOUTUBE",
          "startedBy": "<USER ID>",
          "createdAt": "<ISO 8601 UTC>"
        }
      ]
    ]
    ```

2.  `youtube::playvideo`

    ```json
    42[
      "youtube::playvideo",
      {
        "startTime": "<ISO 8601 UTC>",
        "endTime": "<ISO 8601 UTC + YT DURACTION>",
        "description": null,
        "channelId": "<YT CHANNEL ID STR>",
        "pausedAt": null,
        "createdAt": "<ISO 8601 UTC>",
        "_id": "<JUMPIN'S VIDEO ID STR>",
        "mediaId": "<YT ID STR>",
        "title": "<YT TITLE STR>",
        "link": "<YT SHORT URL STR>",
        "duration": 240,
        "thumb": "<THUMBNAIL URL STR",
        "mediaType": "TYPE_YOUTUBE",
        "startedBy": {
          "profile": {
            "pic": "user-avatar/avatar-blank.png"
          },
          "_id": "<USER ID>",
          "username": "<USER>"
        }
      }
    ]
    ```

## Client Events<a id="sec-1-3"></a>

### Receive<a id="sec-1-3-1"></a>

1.  `client::banlist`

    Includes global bans, no clear way to filter out
    
    ```json
    42[
      "client::banlist",
      {
        "list": [
          {
            "_id": "<BANLIST ID>",
            "handle": "<BANLIST HANDLE>",
            "timestamp": "<ISO 8601 UTC>"
          },
        ]
      }
    ]
    ```

2.  `client::stillConnected`

    ```json
    42[
      "client::stillConnected"
    ]
    ```

3.  `client::handleChange`

    ```json
    42[
      "client::handleChange",
      {
        "handle": "<OWN NICK>"
      }
    ]
    ```

## Self Events<a id="sec-1-4"></a>

### Receive<a id="sec-1-4-1"></a>

1.  `self::join`
    Received after `room::join`
    ```json
    42[
      "self::join",
      {
        "user": {
          "user_id": "<USER ID>",
          "operator_id": "<OP ID>",
          "assignedBy": null,
          "username": "<USER>",
          "isBroadcasting": false,
          "isAdmin": false,
          "isSiteMod": false,
          "isSupporter": false,
          "userIcon": null,
          "_id": "<PUBLIC ID??>",
          "handle": "<TMP NICK>",
          "color": "bluealt",
          "createdAt": "<ISO 8601 UTC>",
          "joinTime": "<ISO 8601 UTC>",
          "operatorPermissions": {
            "ban": true,
            "close_cam": true,
            "mute_user_audio": true,
            "mute_user_chat": true,
            "mute_room_chat": false,
            "mute_room_audio": false,
            "apply_password": false,
            "assign_operator": true,
            "play_youtube": true
          }
        }
      }
    ]
    ```

# API<a id="sec-2"></a>

Base URL: `https://jumpin.chat/api/`

## Youtube<a id="sec-2-1"></a>

### Playlist<a id="sec-2-1-1"></a>

Path: `youtube/<ROOM>/playlist`

Method: `GET`

Response:

```json
[
  {
    "startTime": "<ISO 8601 UTC>",
    "endTime": "<ISO 8601 UTC + DURATION>",
    "description": null,
    "channelId": "<YT CHANNEL ID STR>",
    "pausedAt": null,
    "createdAt": "<ISO 8601 UTC>",
    "_id": "<JUMPIN'S VIDEO ID>",
    "mediaId": "<YT ID STR>",
    "title": "<YT TITLE STR>",
    "link": "<YT SHORT LINK STR>",
    "duration": 240,
    "thumb": "<THUMBNAIL URL STR>",
    "mediaType": "TYPE_YOUTUBE",
    "startedBy": {
      "userId": "<USER ID>",
      "username": "<USER>",
      "pic": "user-avatar/avatar-blank.png"
    }
  }
]
```

### Search<a id="sec-2-1-2"></a>

Path: `youtube/search/<QUERY>`

Method: `GET`

Response: (for query as "abc")

```json
[
  {
    "title": "ABC SONG | ABC Songs for Children - 13 Alphabet Songs &amp; 26 Videos",
    "videoId": "_UR-l3QI2nE",
    "thumb": {
      "url": "https://i.ytimg.com/vi/_UR-l3QI2nE/mqdefault.jpg",
      "width": 320,
      "height": 180
    },
    "channelId": "UCbCmjCuTUZos6Inko4u57UQ",
    "urls": {
      "video": "https://youtu.be/_UR-l3QI2nE",
      "channel": "https://youtube.com/channel/UCbCmjCuTUZos6Inko4u57UQ"
    }
  },
  {
    "title": "ABC Song + More Nursery Rhymes &amp; Kids Songs - CoCoMelon",
    "videoId": "71h8MZshGSs",
    "thumb": {
      "url": "https://i.ytimg.com/vi/71h8MZshGSs/mqdefault.jpg",
      "width": 320,
      "height": 180
    },
    "channelId": "UCbCmjCuTUZos6Inko4u57UQ",
    "urls": {
      "video": "https://youtu.be/71h8MZshGSs",
      "channel": "https://youtube.com/channel/UCbCmjCuTUZos6Inko4u57UQ"
    }
  },
  {
    "title": "ABC Song with Balloons | CoCoMelon Nursery Rhymes &amp; Kids Songs",
    "videoId": "RIQDmnIJZv8",
    "thumb": {
      "url": "https://i.ytimg.com/vi/RIQDmnIJZv8/mqdefault.jpg",
      "width": 320,
      "height": 180
    },
    "channelId": "UCbCmjCuTUZos6Inko4u57UQ",
    "urls": {
      "video": "https://youtu.be/RIQDmnIJZv8",
      "channel": "https://youtube.com/channel/UCbCmjCuTUZos6Inko4u57UQ"
    }
  },
  {
    "title": "ABC Song | Wendy Pretend Play Learning Alphabet w/ Toys &amp; Nursery Rhyme Songs",
    "videoId": "BNTCpF_n6J4",
    "thumb": {
      "url": "https://i.ytimg.com/vi/BNTCpF_n6J4/mqdefault.jpg",
      "width": 320,
      "height": 180
    },
    "channelId": "UCgFXm4TI8htWmCyJ6cVPG_A",
    "urls": {
      "video": "https://youtu.be/BNTCpF_n6J4",
      "channel": "https://youtube.com/channel/UCgFXm4TI8htWmCyJ6cVPG_A"
    }
  },
  {
    "title": "Alphabet Song | ABC Song | Phonics Song",
    "videoId": "36IBDpTRVNE",
    "thumb": {
      "url": "https://i.ytimg.com/vi/36IBDpTRVNE/mqdefault.jpg",
      "width": 320,
      "height": 180
    },
    "channelId": "UC1jhiDqp-jIYR07Ini8Jamw",
    "urls": {
      "video": "https://youtu.be/36IBDpTRVNE",
      "channel": "https://youtube.com/channel/UC1jhiDqp-jIYR07Ini8Jamw"
    }
  }
]
```

## Room<a id="sec-2-2"></a>

### Users<a id="sec-2-2-1"></a>

Path: `rooms/<ROOM>`

Method: `GET`

Response:

```json
{
  "_id": "<ROOM ID>",
  "name": "tech",
  "users": [
    {
      "_id": "<CURRENT ID>",
      "handle": "matriarch",
      "operator_id": "<OP ID>",
      "user_id": "<USER ID>",
      "username": "<ACCOUNT>",
      "isBroadcasting": true,
      "assignedBy": null,
      "isAdmin": false,
      "isSiteMod": false,
      "isSupporter": false,
      "userIcon": null,
      "color": "aquaalt"
    },
  ],
  "attrs": {
    "owner": "<USER ID>",
    "janus_id": 1782420776,
    "fresh": false,
    "ageRestricted": false
  },
  "settings": {
    "public": true,
    "modOnlyPlayMedia": true,
    "forcePtt": true,
    "forceUser": false,
    "description": "Technology, Games, and THC 18+ ☮ Discord.gg/UpDZMB3 ☮",
    "topic": {
      "text": "Join our Discord server!",
      "updatedAt": "<ISO 8601 UTC>",
      "updatedBy": {
        "_id": "<USER ID>",
        "username": "<ROOM>"
      }
    },
    "display": "room-display/display-tech.jpg",
    "requiresPassword": false
  }
}
```

### Unread<a id="sec-2-2-2"></a>

Path: `message/<USER ID>/unread`

Method: `GET`

Response:

```json
{
  "unread": 0
}
```

TODO: Sort this out

### Profile<a id="sec-2-2-3"></a>

Path: `user/<USER ID>/profile`

Method: `GET`

Response:

```json
{
  "username": "<ACCOUNT>",
  "joinDate": "<ISO 8601 UTC>",
  "lastActive": "<ISO 8601 UTC>",
  "location": null,
  "pic": "user-avatar/avatar-blank.png",
  "trophies": [],
  "trophyCount": 0,
  "userType": "registered user"
}
```

### Emoji<a id="sec-2-2-4"></a>

Path: `rooms/tech/emoji`

Method: `GET`

Response:

```json
[]
```
