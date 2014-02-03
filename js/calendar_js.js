/**********************************************************************
*          Calendar JavaScript [DOM] v3.11 by Michael Loesler          *
************************************************************************
* Copyright (C) 2005-09 by Michael Loesler, http//derletztekick.com    *
*                                                                      *
*                                                                      *
* This program is free software; you can redistribute it and/or modify *
* it under the terms of the GNU General Public License as published by *
* the Free Software Foundation; either version 3 of the License, or    *
* (at your option) any later version.                                  *
*                                                                      *
* This program is distributed in the hope that it will be useful,      *
* but WITHOUT ANY WARRANTY; without even the implied warranty of       *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        *
* GNU General Public License for more details.                         *
*                                                                      *
* You should have received a copy of the GNU General Public License    *
* along with this program; if not, see <http://www.gnu.org/licenses/>  *
* or write to the                                                      *
* Free Software Foundation, Inc.,                                      *
* 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.            *
*                                                                      *
 **********************************************************************/
 
    function CalendarJS() {
        this.now = new Date();
        this.dayname = ["Mo","Di","Mi","Do","Fr","Sa","So"];
        this.monthname = ["Januar","Februar","M\u00e4rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];    
        this.tooltip = ["vorheriger Monat","n\u00e4chster Monat","aktuelles Datum","vorheriges Jahr","n\u00e4chstes Jahr"];
        this.monthCell = document.createElement("th");
        this.tableHead = null;
		this.tableFoot = null;
        this.parEl = null;
 
        this.init = function( id, initDate ) {
            this.now = initDate?initDate:new Date();
            this.date = this.now.getDate();
            this.month = this.mm = this.now.getMonth();
            this.year = this.yy = this.now.getFullYear();
            this.monthCell.appendChild(document.createTextNode( this.monthname[this.mm]+"\u00a0"+this.yy ));
            this.tableHead = this.createTableHead();
            this.tableFoot = this.createTableFoot();
            this.parEl = document.getElementById( id );
            this.show();
            if (!initDate) this.checkDate();
        },
 
        this.checkDate = function() {
            var self = this;
            var today = new Date();
            if (this.date != today.getDate()) {
			
				this.tableHead = this.createTableHead();
				this.tableFoot = this.createTableFoot();
				
                this.date = today.getDate();
                if (this.mm == this.month && this.yy == this.year)
                    this.switchMonth("current");
                this.month = today.getMonth();
                if (this.mm == this.month && this.yy == this.year)
                    this.switchMonth("current");
                this.year  = today.getFullYear();
                if (this.mm == this.month && this.yy == this.year)
                    this.switchMonth("current");
            }
            window.setTimeout(function() { self.checkDate(); }, Math.abs(new Date(this.year, this.month, this.date, 24, 0, 0)-this.now));
        },
 
        this.removeElements = function( Obj ) {
            while( Obj.childNodes.length > 0)
                Obj.removeChild(Obj.childNodes[Obj.childNodes.length-1]);
            return Obj;
        },
		
        this.show = function() {
            this.parEl = this.removeElements( this.parEl );
            this.monthCell.firstChild.replaceData(0, this.monthCell.firstChild.nodeValue.length, this.monthname[this.mm]+"\u00a0"+this.yy);
            var table = document.createElement("table");
            table.appendChild( this.createTableBody() );
            table.appendChild( this.tableHead );
            table.appendChild( this.tableFoot );
            this.parEl.appendChild( table );
        },
		
		this.createTableFoot = function() {
			var tfoot = document.createElement("tfoot");
			var tr = document.createElement("tr");
			var td = this.getCell( "td", "KW\u00a0" + this.getCalendarWeek(this.year, this.month, this.date), "calendar_week" );
			td.colSpan = 3;
			tr.appendChild( td );
			var td = this.getCell( "td", this.timeTrigger(), "clock" );
			td.colSpan = 4;
			tr.appendChild( td );
			tfoot.appendChild( tr );
			var self = this;
			window.setInterval(function() { td.firstChild.nodeValue = self.timeTrigger(); }, 500);
			return tfoot;
		}
		
        this.createTableHead = function() {
            var thead = document.createElement("thead");
            var tr = document.createElement("tr");
            var th = this.getCell( "th", "\u00AB", "prev_month" );
			th.rowSpan = 2;
            th.Instanz = this;
            th.onclick = function() { this.Instanz.switchMonth("prev"); };
            th.title = this.tooltip[0];
            try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
            tr.appendChild( th );
            this.monthCell.Instanz = this;
			this.monthCell.rowSpan = 2;
			this.monthCell.colSpan = 4;
            this.monthCell.onclick = function() { this.Instanz.switchMonth("current"); };
            this.monthCell.title = this.tooltip[2];
            try { this.monthCell.style.cursor = "pointer"; } catch(e){ this.monthCell.style.cursor = "hand"; }
            tr.appendChild( this.monthCell );    
            th = this.getCell( "th", "\u00BB", "next_month" );
			th.rowSpan = 2;
            th.Instanz = this;
            th.onclick = function() { this.Instanz.switchMonth("next"); };
            th.title = this.tooltip[1];
            try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
            tr.appendChild( th );
			th = this.getCell( "th", "\u02c4", "prev_year" );
            th.Instanz = this;
            th.onclick = function() { this.Instanz.switchMonth("prev_year"); };
            th.title = this.tooltip[3];
            try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
            tr.appendChild( th );
			thead.appendChild( tr );
			tr = document.createElement("tr");
            th = this.getCell( "th", "\u02c5", "next_year" )
            th.Instanz = this;
            th.onclick = function() { this.Instanz.switchMonth("next_year"); };
            th.title = this.tooltip[4];
            try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
            tr.appendChild( th );
            thead.appendChild( tr );
            tr = document.createElement('tr');
            for (var i=0; i<this.dayname.length; i++)
                tr.appendChild( this.getCell("th", this.dayname[i], "weekday" ) );
            thead.appendChild( tr );
            return thead;
        },
 
        this.createTableBody = function() {
            var dayspermonth = [31,28,31,30,31,30,31,31,30,31,30,31];
            var sevendaysaweek = 0;
            var begin = new Date(this.yy, this.mm, 1);
            var firstday = begin.getDay()-1;
            if (firstday < 0)
                firstday = 6;
            if ((this.yy%4==0) && ((this.yy%100!=0) || (this.yy%400==0)))
                dayspermonth[1] = 29;
            var tbody = document.createElement("tbody");
            var tr = document.createElement('tr');
            if (firstday == 0) {
                for (var i=0; i<this.dayname.length; i++) {
                    var prevMonth = (this.mm == 0)?11:this.mm-1;
                    tr.appendChild( this.getCell( "td", dayspermonth[prevMonth]-6+i, "last_month" ) );
                }
                tbody.appendChild( tr );
                tr = document.createElement('tr');
            }
 
            for (var i=0; i<firstday; i++, sevendaysaweek++) {
                var prevMonth = (this.mm == 0)?11:this.mm-1;
                tr.appendChild( this.getCell( "td", dayspermonth[prevMonth]-firstday+i+1, "last_month" ) );
 
            }
 
            for (var i=1; i<=dayspermonth[this.mm]; i++, sevendaysaweek++){
                if (this.dayname.length == sevendaysaweek){
                    tbody.appendChild( tr );
                    tr = document.createElement('tr');
                    sevendaysaweek = 0;
                }
 
                var td = null;
                if (i==this.date && this.mm==this.month && this.yy==this.year && (sevendaysaweek == 5 || sevendaysaweek == 6))
                    td = this.getCell( "td", i, "today weekend" );
                else if (i==this.date && this.mm==this.month && this.yy==this.year)
                    td = this.getCell( "td", i, "today" );
                else if (sevendaysaweek == 5 || sevendaysaweek == 6)
                    td = this.getCell( "td", i, "weekend" );
                else
                    td = this.getCell( "td", i, null ); 
 
                td.setDate = this.setDate;
                td.dd = i;
                td.mm = this.mm;
                td.yy = this.yy;
                td.onclick = function(e) {
                    var currentDate = new Date(this.yy, this.mm, this.dd);
                    this.setDate( currentDate );
                };
                tr.appendChild( td );
            }
 
            var daysNextMonth = 1;
            for (var i=sevendaysaweek; i<this.dayname.length; i++) 
                tr.appendChild( this.getCell( "td", daysNextMonth++, "next_month"  ) );
 
            tbody.appendChild( tr );
 
            while (tbody.getElementsByTagName("tr").length<6) {
                tr = document.createElement('tr');
                for (var i=0; i<this.dayname.length; i++) 
                    tr.appendChild( this.getCell( "td", daysNextMonth++, "next_month"  ) );
                tbody.appendChild( tr );
            }
 
            return tbody;
 
        },
		
		this.getCalendarWeek = function(j,m,t) {
			var cwDate = this.now;
			if (!t) {
				j = cwDate.getFullYear();
				m = cwDate.getMonth(); 
				t = cwDate.getDate();
			}
			cwDate = new Date(j,m,t);
			var doDat = new Date(cwDate.getTime() + (3-((cwDate.getDay()+6) % 7)) * 86400000);
			cwYear = doDat.getFullYear();
			var doCW = new Date(new Date(cwYear,0,4).getTime() + (3-((new Date(cwYear,0,4).getDay()+6) % 7)) * 86400000);
			cw = Math.floor(1.5+(doDat.getTime()-doCW.getTime())/86400000/7);
			return cw;
		},
		 
        this.setDate = function(date) {
            // Weiterverarbeitung des geklickten Datums
            // window.alert( date );
        },
		
		this.timeTrigger = function(){
			var now = new Date();
			var ss = (now.getSeconds()<10)?"0"+now.getSeconds():now.getSeconds();
			var mm = (now.getMinutes()<10)?"0"+now.getMinutes():now.getMinutes();
			var hh  = (now.getHours()<10)?"0"+now.getHours():now.getHours();
			var str = hh+":"+mm+":"+ss;
			return str;
		},

        this.getCell = function(tag, str, cssClass) {
            var El = document.createElement( tag );
            El.appendChild(document.createTextNode( str ));
            if (cssClass != null)
                El.className = cssClass;
            return El;
        },
 
        this.switchMonth = function( s ){
            switch (s) {
                case "prev": 
                    this.yy = (this.mm == 0)?this.yy-1:this.yy;
                    this.mm = (this.mm == 0)?11:this.mm-1;
                break;
 
                case "next":
                    this.yy = (this.mm == 11)?this.yy+1:this.yy;
                    this.mm = (this.mm == 11)?0:this.mm+1;
                break;
                case "prev_year": 
                    this.yy = this.yy-1;
                break;
 
                case "next_year":
                    this.yy = this.yy+1;
                break;
                case "current":
                    this.yy = this.year;
                    this.mm = this.month;
                break;
            }
            this.show();
        }
    }
 
    var DOMContentLoaded = false;
    function addContentLoadListener (func) {
        if (document.addEventListener) {
            var DOMContentLoadFunction = function () {
                window.DOMContentLoaded = true;
                func();
            };
            document.addEventListener("DOMContentLoaded", DOMContentLoadFunction, false);
        }
        var oldfunc = (window.onload || new Function());
        window.onload = function () {
            if (!window.DOMContentLoaded) {
                oldfunc();
                func();
            }
        };
    }
 
    addContentLoadListener( function() { 
            new CalendarJS().init("calendar");
            //new CalendarJS().init("calendar", new Date(2009, 1, 15));
    } );