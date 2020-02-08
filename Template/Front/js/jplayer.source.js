
//<![CDATA[

$(document).ready(function() {

	

	// homepage audio-player

	

	new jPlayerPlaylist({

		jPlayer: "#tr-player",

		cssSelectorAncestor: "#tr-player-wrapper"

	}, [

		{

			title:"Cro Magnon Man",

			mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

			oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg"

		},

		{

			title:"Stirring of a Fool",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-08-Stirring-of-a-fool.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-08-Stirring-of-a-fool.ogg"

		},

		{

			title:"Your Face",

			mp3:"http://www.jplayer.org/audio/mp3/TSP-05-Your_face.mp3",

			oga:"http://www.jplayer.org/audio/ogg/TSP-05-Your_face.ogg"

		},

		{

			title:"Cyber Sonnet",

			mp3:"http://www.jplayer.org/audio/mp3/TSP-07-Cybersonnet.mp3",

			oga:"http://www.jplayer.org/audio/ogg/TSP-07-Cybersonnet.ogg"

		},

		{

			title:"Tempered Song",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-01-Tempered-song.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-01-Tempered-song.ogg"

		},

		{

			title:"Hidden",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-02-Hidden.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-02-Hidden.ogg"

		},

		{

			title:"Lentement",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-03-Lentement.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-03-Lentement.ogg"

		},

		{

			title:"Lismore",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-04-Lismore.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-04-Lismore.ogg"

		},

		{

			title:"The Separation",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-05-The-separation.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-05-The-separation.ogg"

		},

		{

			title:"Beside Me",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-06-Beside-me.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-06-Beside-me.ogg"

		},

		{

			title:"Bubble",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-07-Bubble.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"

		},

		

		{

			title:"Partir",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-09-Partir.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-09-Partir.ogg"

		},

		{

			title:"Thin Ice",

			mp3:"http://www.jplayer.org/audio/mp3/Miaow-10-Thin-ice.mp3",

			oga:"http://www.jplayer.org/audio/ogg/Miaow-10-Thin-ice.ogg"

		}

	], {

		swfPath: "js/jquery.jplayer.swf",

		supplied: "oga, mp3",

		wmode: "window",

		useStateClassSkin: true,

		autoBlur: false,

		smoothPlayBar: true,

		keyEnabled: true,

		remainingDuration: true,

	});

	



});





//<![CDATA[

    $(document).ready(function(){

        // ========= jPlayer Album Detail Page ==========

        var myPlaylist = new jPlayerPlaylist({

            jPlayer: "#jplayer",

            cssSelectorAncestor: "#jp-container"

        }, [

            {

                artist: "Gibson", // the artist name

                title:"<span>01.</span> Afrojack - Lionheart (Original Mix)", // track title

                mp3:"http://www.jplayer.org/audio/mp3/Miaow-07-Bubble.mp3",// mp3 path

                oga:"http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg",// oga path

                duration: '5:12'// duration time in playlist

            },

            {

                artist: "Cetinkaya",

                title:"<span>02.</span> Divine X - Together (audiobotz Remix)",

                mp3:"http://www.jplayer.org/audio/mp3/Miaow-01-Tempered-song.mp3",

                oga:"http://www.jplayer.org/audio/ogg/Miaow-01-Tempered-song.ogg",

                duration: '4:22'

            },

            {

                artist: "Putnam",

                title:"<span>03.</span> Dwayne DJ Bravo - Champion",

                mp3:"http://www.jplayer.org/audio/mp3/Miaow-02-Hidden.mp3",

                oga:"http://www.jplayer.org/audio/ogg/Miaow-02-Hidden.ogg",

                duration: '4:36'

            },

            {

                artist: "Thomas",

                title:"<span>04.</span> LaRoxx Project - Sunshine Love",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '2:51'

            },

            {

                artist: "shvili",

                title:"<span>05.</span> Love You Like A Love Song",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '3:12'

            },

            {

                artist: "Wong",

                title:"<span>06.</span> One More Night",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '4:47'

            },

            {

                artist: "Fisher",

                title:"<span>07.</span> Fun ft. Chris Brown",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '4:00'

            },

            {

                artist: "Siljaklik",

                title:"<span>08.</span> Part Of Me",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '5:27'

            },

            {

                artist: "Gibson",

                title:"<span>09.</span> This Is Love ft. Eva Simons",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '4:10'

            },

            {

                artist: "Klepic",

                title:"<span>10.</span> Hall of Fame ft. will.i.am",

                mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                duration: '5:30'

            }

        ], {

            swfPath: "assets/jplayer/jplayer",

            supplied: "oga, mp3",

            wmode: "window",

            useStateClassSkin: true,

            autoBlur: false,

            smoothPlayBar: true,

            keyEnabled: true,

            size: {width: "100%"}

        });

        // Show The Current Track !!

        $("#jplayer").on($.jPlayer.event.ready, function (event) {

            var current = myPlaylist.current;

            var playlist = myPlaylist.playlist;       

            $.each(playlist, function (index, obj) {

                if (index == current) {

                    $("#playing").html("<span class='artist-name'>" + obj.artist + "</span>" + "<br>" + "<span class='track-name'>" + obj.title + "</span>");

                }

            });

        });

        $("#jplayer").on($.jPlayer.event.play, function (event) {

            var current = myPlaylist.current;

            var playlist = myPlaylist.playlist;       

            $.each(playlist, function (index, obj) {

                if (index == current) {

                    $("#playing").html("<span class='artist-name'>" + obj.artist + "</span>" + "<br>" + "<span class='track-name'>" + obj.title + "</span>");

                }

            });

        });



    });//]]>







//<![CDATA[

    $(document).ready(function(){

    	// ========= About Page ==========

        $("#music").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                });

            },

           

        });



        $("#music1").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/TSP-07-Cybersonnet.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/TSP-07-Cybersonnet.ogg"

                });

            },

            cssSelectorAncestor: '#music-1'

        });



        $("#music2").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/Miaow-09-Partir.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/Miaow-09-Partir.ogg"

                });

            },

            cssSelectorAncestor: '#music-2'

        });



        $("#music3").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                });

            },

            cssSelectorAncestor: '#music-3'

        });



        $("#music4").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/TSP-05-Your_face.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/TSP-05-Your_face.ogg"

                });

            },

            cssSelectorAncestor: '#music-4'

        });



        $("#music5").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/Miaow-08-Stirring-of-a-fool.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/Miaow-08-Stirring-of-a-fool.ogg"

                });

            },

            cssSelectorAncestor: '#music-5'

        });



        $("#music6").jPlayer({

            ready: function () {

                $(this).jPlayer("setMedia", {

                    mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",

                    oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",

                });

            },

            cssSelectorAncestor: '#music-6'

        });

    });//]]> 

  