define(
    [
        'jquery'
    ],
    function ($) {
        "use strict";
        return {
            map: '',
            bounds: '',
            markers: [],
            currentInfoWindow: false,
            homeIcon: false,
            relayIcon: false,
            createMap: function(elementId) {
                var self = this;
                var myOptions = {
                    zoom: 5,
                    center: new google.maps.LatLng(47.37285025362682, 2.4172996312499784),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                this.map =  new google.maps.Map(document.getElementById(elementId), myOptions);
                this.updateFunctionalities();
                this.bounds = new google.maps.LatLngBounds();
                this.markers = [];

                google.maps.event.addDomListener(window, "resize", function() {
                    self.updateFunctionalities();
                });

                return this;
            },
            mustDisableFunctionalities: function () {
                return 'ontouchstart' in window || navigator.msMaxTouchPoints || $(window).width() < 768;
            },
            updateFunctionalities: function() {
                if(this.mustDisableFunctionalities()) { /* on désactive qq fonctionnalités en mobile pour améliorer la navigation */
                    this.map.setOptions({draggable: false,scrollwheel: false});
                } else {
                    this.map.setOptions({draggable: true,scrollwheel: true});
                }
            },
            /**
             *
             * @param relayPoint
             * @param type (relay or home)
             * @param trads
             */
            addMarker: function(relayPoint,type,trads) {
                var self = this;

                var relayaddress = relayPoint.adresse1;
                if(relayPoint.codePostal)
                    relayaddress += " "+relayPoint.codePostal;
                if(relayPoint.localite)
                    relayaddress += " "+relayPoint.localite;

                var geo = new google.maps.Geocoder();

                /*var blueIcon = new google.maps.MarkerImage(Picto_Chrono_Relais);
                var homeIcon = new google.maps.MarkerImage(Home_Chrono_Icon);*/

                geo.geocode({'address': relayaddress}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {

                        var point = results[0].geometry.location;

                        self.createdTabbedMarker(point,relayPoint,type,trads);

                        self.map.setCenter(point, 11);
                        self.bounds.extend(point);

                        self.map.fitBounds(self.bounds);
                        self.map.setCenter(self.bounds.getCenter());


                    }
                });
            },
            createdTabbedMarker: function(point,relayPoint,type,trads) {
                var self = this;

                var marker = new google.maps.Marker({
                    position: point,
                    map: self.map,
                    title: type == 'home' ? 'home' : relayPoint.nomEnseigne,
                    icon: type == 'home' ? new google.maps.MarkerImage(self.homeIcon) : new google.maps.MarkerImage(self.relayIcon)
                });

                if(type == 'home') { /* si point home : pas de click */
                    return;
                }

                var relayPoindId = relayPoint.identifiantChronopostPointA2PAS;
                var infowindow;
                if (window.innerWidth < 700) {
                    infowindow = new google.maps.InfoWindow({
                        content: '<div style=""><div style="float: left;"><h2>' + trads.informations + '</h2>'+self.getMarkerInfoContent(relayPoint)+'</div><div style="margin-left: 10px; padding-left: 10px; border-left: 1px solid #000; float: left;"><h2>' + trads.horaires + '</h2><div style="">'+self.getHorairesTab(relayPoint, true, trads)+'</div></div></div>'
                    });
                } else {
                    infowindow = new google.maps.InfoWindow({
                        content: '<div style="width: 400px;"><div style="width: 190px; float: left;"><h2>' + trads.informations + '</h2>'+self.getMarkerInfoContent(relayPoint)+'</div><div style="margin-left: 10px; padding-left: 10px; border-left: 1px solid #000; float: left;"><h2>' + trads.horaires + '</h2><div style="width: 189px">'+self.getHorairesTab(relayPoint, true, trads)+'</div></div></div>'
                    });
                }
                google.maps.event.addListener(marker, 'click', function() {
                    if(document.getElementById('s_method_chronorelais_'+relayPoint.identifiantChronopostPointA2PAS)) {
                        document.getElementById('s_method_chronorelais_'+relayPoint.identifiantChronopostPointA2PAS).checked = true;
                        $('#s_method_chronorelais_'+relayPoint.identifiantChronopostPointA2PAS).trigger('setSession');
                    }


                    if (self.currentInfoWindow) {
                        self.currentInfoWindow.close();
                    }
                    infowindow.open(self.map,marker);
                    self.currentInfoWindow = infowindow;
                });

                self.markers[relayPoindId] = marker;
            },
            getMarkerInfoContent: function(relayPoint){
                var icoPath = ''; //Picto_Chrono_Relais;
                return "<div class=\"sw-map-adresse-wrp\" style=\"background-image: url("+ icoPath +"); background-repeat: no-repeat;padding-left:50px;\">"
                    + "<h2>"+relayPoint.nomEnseigne+"</h2>"
                    + "<div class=\"sw-map-adresse\">"
                    + this.parseAdresse(relayPoint)
                    + relayPoint.codePostal + " " + relayPoint.localite
                    + "</div></div>";
            },
            getHorairesTab: function (anArray, highlight, trads)
            {
                var userAgent = navigator.userAgent.toLowerCase();
                var msie = /msie/.test( userAgent ) && !/opera/.test( userAgent );

                var rs = "" ;
                rs =  "<table id=\"sw-table-horaire\" class=\"sw-table\"";
                if(msie) {
                    rs +=  " style=\"width:auto;\"";
                }
                rs +=  ">"
                    + "<tr><td>" + trads.lundi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureLundi, 1, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.mardi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureMardi, 2, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.mercredi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureMercredi, 3, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.jeudi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureJeudi, 4, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.vendredi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureVendredi, 5, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.samedi + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureSamedi, 6, highlight, trads) +"</tr>"
                    + "<tr><td>" + trads.dimanche + "</td>"+ this.parseHorairesOuverture(anArray.horairesOuvertureDimanche, 0, highlight, trads) +"</tr>"
                    + "</table>" ;
                return rs ;
            },
            parseAdresse: function(anArray)
            {
                var address = anArray.adresse1 + "<br />" ;
                if (anArray.adresse2)
                    address += anArray.adresse2 + "<br />" ;
                if (anArray.adresse3)
                    address += anArray.adresse3 + "<br />" ;
                return address ;
            },
            parseHorairesOuverture: function(value , day, highlight, trads)
            {
                var rs = "" ;

                var now = new Date() ;
                var attributedCell = "" ;
                var reg = new RegExp(" ", "g");

                var horaires = value.split(reg) ;

                for (var i=0; i < horaires.length; i++)
                {
                    attributedCell = "" ;

                    // so, re-format time
                    if (horaires[i] == "00:00-00:00")
                    {
                        horaires[i] = "<td "+attributedCell+">"+trads.ferme+"</td>" ;
                    }
                    else
                    {
                        horaires[i] = "<td "+attributedCell+">"+horaires[i]+"</td>" ;
                    }
                    // yeah, concatenates result to the returned value
                    rs += horaires[i] ;
                }

                return rs ;
            },
            loadMyPoint: function(relayPointId) {
                google.maps.event.trigger(this.markers[relayPointId], "click");
            },
            setHomeIcon: function(icon) {
                this.homeIcon = icon;
            },
            setRelayIcon: function(icon) {
                this.relayIcon = icon;
            }
        };
    }
);

