$(document).ready(function() {

	init();

	// Category menu onchange reload toplist with new data
	$(".apk-category-menu").change(function () {
		var category = this.value;
		
		$.ajax({
			method: "POST",
			url: "../apkApi.php",
			data: { method: "get_articles", category: category }
		})
		.done(function( msg ) {
			updateSelected(category);
			updateTable(JSON.parse(msg), 0);
		});
	});

	// Load more button
	$("#apk-toplist__loadmorebtn").on('click', function () {
		var category = $(".apk-category-menu option").filter(":selected").val();
		var offset = $(".apk-data-body tr").length;

		$.ajax({
			method: "POST",
			url: "../apkApi.php",
			data: { method: "get_more_articles", category: category, offset: offset }
		})
		.done(function( msg ) {
			updateTable(JSON.parse(msg), offset);
		});
	});



	/* Functions */

	function init() {
		// Initial call to load toplist
		$.ajax({
			method: "POST",
			url: "../apkApi.php",
			data: { method: "get_articles", category: "toplist" }
		})
		.done(function( msg ) {
			updateSelected("toplist");
			updateTable(JSON.parse(msg), 0);
		});

		// Call to load table2
		$.ajax({
			method: "POST",
			url: "../apkApi.php",
			data: { method: "get_worst_apk_articles" }
		})
		.done(function( msg ) {
			updateTable2(JSON.parse(msg));
		});
	}

	// Update the toplist table
	function updateTable(data, offsetused) {
		var tbody = $('.apk-table .apk-data-body');

		// Remove the data if no offset
		if (offsetused === 0) {
			tbody.empty();
		}

		// Make rows
		for (var i = 0; i < data.length; i++) {
			var markup = '';
			// Round stuff
			var alkoholhalt = +(data[i]['alkoholhalt'] * 100).toFixed(8);
			var pris = parseFloat(data[i]["pris"]);

			// Start creating the row markup
			markup += '<tr>';
			markup += '<td class="apk-table-rank">'+(offsetused+i+1)+'</td>';
			markup += '<td><a href="'+data[i]['producturl']+'" class="product-link">';
			markup += '<img src="image/systemet-logga-empty.png" alt="Systemets logotyp" id="systemet-logo">';
			markup += data[i]['namn'];
			markup += '</a> <small>'+data[i]['namn2']+'</small></td>';
			markup += '<td class="apk-table-percent">'+alkoholhalt+'%</td>';
			markup += '<td class="apk-table-cost">'+pris+' kr</td>';
			markup += '<td class="apk-table-group">'+data[i]["varugrupp"]+'</td>';
			markup += '<td class="apk-table-packaging">'+data[i]["forpackning"]+'</td>';
			markup += '<td class="apk-table-volume">'+data[i]["volym"]+' ml</td>';
			markup += '<td class="apk-table-apk apk-text-value">'+data[i]["apk"]+'</td>';
			markup += '</tr>';
			tbody.append(markup);
		}
	}

	// Update table2
	function updateTable2(data) {
		var tbody = $('.apk-table2 .apk-data-body');

		// Make rows
		for (var i = 0; i < data.length; i++) {
			var markup = '';
			// Round stuff
			var alkoholhalt = +(data[i]['alkoholhalt'] * 100).toFixed(8);
			var pris = parseFloat(data[i]["pris"]);

			// Start creating the row markup
			markup += '<tr>';
			markup += '<td class="apk-table-rank">'+(i+1)+'</td>';
			markup += '<td><a href="'+data[i]['producturl']+'" class="product-link">';
			markup += '<img src="image/systemet-logga-empty.png" alt="Systemets logotyp" id="systemet-logo">';
			markup += data[i]['namn'];
			markup += '</a> <small>'+data[i]['namn2']+'</small></td>';
			markup += '<td class="apk-table-percent">'+alkoholhalt+'%</td>';
			markup += '<td class="apk-table-cost">'+pris+' kr</td>';
			markup += '<td class="apk-table-group">'+data[i]["varugrupp"]+'</td>';
			markup += '<td class="apk-table-packaging">'+data[i]["forpackning"]+'</td>';
			markup += '<td class="apk-table-volume">'+data[i]["volym"]+' ml</td>';
			markup += '<td class="apk-table-apk apk-text-value">'+data[i]["apk"]+'</td>';
			markup += '</tr>';
			tbody.append(markup);
		}
	}

	// Set the dropdown options to selected
	function updateSelected(category) {
		// Remove all other "selected" attributes
		$(".apk-category-menu option").each(function() {
			$(".apk-category-menu option").attr("selected", false);
		});

		$(".apk-category-menu option[value='"+category+"']").attr("selected", "selected");
	}
});