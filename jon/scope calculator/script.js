function getDocumentData() {
    //get the current document and the body of the doc
    var ui = DocumentApp.getUi();
    let currDoc = DocumentApp.getActiveDocument();
    let body = currDoc.getBody()
    let document_text = body.getText();
    let headers = getHeaders();
    //define hour search elements
    let searchType = DocumentApp.ElementType.TABLE;
    let searchResult = null
  
    //define lower and upper ranges of hours
    let lowerRanges = [];
    let upperRanges = [];
    let allRanges = [];
    let totalFinalRowCount = 0;
  
  //search for hours and correspondind headers
    while (searchResult = body.findElement(searchType, searchResult)) {
      let table = searchResult.getElement()
      var tableTextContent = table.asText().getText();
      let tableColCount = table.getRow(0).getNumCells()
      let numRows = table.getNumRows();
      let rowStart = 0;
      if (table.getRow(numRows-1).getText().toLowerCase().includes("total")) {
        //only use this row because this row is the total of the other rows
        console.log(table.getRow(numRows-1).getText())
        rowStart = numRows-1;
      }
  
      //loop through each row in the table and try to find the hours
      for (var row=rowStart;row<numRows;row++) {
        let textContent = table.getRow(row).getText();
  
        //trim document top to bottom to found table
        let text_to_search = document_text.slice(0,document_text.indexOf(textContent)+textContent.length);
        document_text = document_text.replace(text_to_search,"");
        let last_header_index;
        let headers_found = []
  
        
        //find all headers included in this subset of text
        for (var h=0;h<headers.length;h++) {
          if (text_to_search.includes(headers[h].text)) {
            headers_found.push(headers[h])
            last_header_index = h;
          } else {
            break;
          }
        }
  
        //shorten headers to last found header index
        headers = headers.slice(last_header_index+1)
  
        if (textContent.includes("Total Scope Time:") || tableColCount != 2) {
          continue;
        } else {
          //headings object used to find top most heading level
          let headings = {
            HEADING1: "",
            HEADING2: "",
            HEADING3: "",
            HEADING4: "",
            HEADING5: ""
          }
          let heading = '';
          
          //append found headers to heaadings object
          for (var z=0;z<headers_found.length;z++) {
            if (headings[headers_found[z].type])
              headings[headers_found[z].type] += " & " + headers_found[z].text;
            else 
              headings[headers_found[z].type] = headers_found[z].text
          }
  
          //get top most heading
          for (const [key,value] of Object.entries(headings)) {
            if (value) {
              heading = value;
              //console.log(heading)
              break;
            }
          }
  
          totalFinalRowCount++
          let hourRange = textContent.match(/\d?\.?\d{1,}.?-.?\d?\.?\d{1,}/) //match something like 1.5 - 5.6
          
          if (hourRange) { 
            //if range of hours split range into lower and upper values
            
            console.log('range of hours',hourRange[0])
            hourRange = hourRange[0];
            let ranges = hourRange.split("-")
            console.log(ranges)
            lowerRanges.push(ranges[0].trim())
            upperRanges.push(ranges[1].trim())
            allRanges.push({hour: hourRange, heading: heading})
          } else {
            let singleHour = textContent.match(/\d?\.?\d{1,}/) //match something like 1.5 or 1 or .5
            if (singleHour) {
              console.log('single',singleHour)
              singleHour = singleHour[0];
              lowerRanges.push(singleHour.trim())
              upperRanges.push(singleHour.trim())
              allRanges.push({hour: singleHour, heading: heading})
            } 
          }
        }
      }
      
    }
  
    return {
      totalRows: totalFinalRowCount,
      lowerRanges: lowerRanges,
      upperRanges: upperRanges,
      allRanges: allRanges
    }
    
  }
  
  function appendToTable(hoursObject) {
    let currDoc = DocumentApp.getActiveDocument();
    let body = currDoc.getBody()
  
    //define hour search elements
    let searchType = DocumentApp.ElementType.TABLE;
    let searchResult = null
  
     while (searchResult = body.findElement(searchType, searchResult)) {
       var cellStyle = {};
    cellStyle[DocumentApp.Attribute.BOLD] = false;
    cellStyle[DocumentApp.Attribute.FOREGROUND_COLOR] = '#000000';
       let table = searchResult.getElement();
       if (table.getRow(0).getNumCells() >3) {
  
         //insert table rows for number of ranges
         for (var j=0;j<hoursObject.allRanges.length;j++) {
          let tr = table.insertTableRow(j+1);
          for (var i=0;i<4;i++) {
            content = '';
            if (i===0) {
              content = hoursObject.allRanges[j].heading
            }
            if (i===2) {
              content = hoursObject.allRanges[j].hour
            } 
            var td = tr.appendTableCell(content);
            td.setAttributes(cellStyle)
          }
         }
         
        table.getRow(table.getNumRows()-1).getCell(2).setText(`${sumTotals(hoursObject.lowerRanges)}-${sumTotals(hoursObject.upperRanges)}`)
       }
     }
  }
  
  function onOpen() {
      var ui = DocumentApp.getUi();
    // Or DocumentApp or FormApp.
    ui.createMenu('Scope Calculator')
        .addItem('Sum Document Totals', 'runSumScript')
        .addToUi();
  
  }
  
  function sumTotals(array) {
    return array.reduce((accumulator, currentValue) => parseFloat(accumulator) + parseFloat(currentValue))
  }
  
  function runSumScript() {
    let hourData = getDocumentData();
    appendToTable(hourData)
  }
  
  
  function getHeaders() {
    let currDoc = DocumentApp.getActiveDocument();
    let body = currDoc.getBody();
    let headers = [];
    let searchType = DocumentApp.ElementType.PARAGRAPH;
    let searchResult = null
    while (searchResult = body.findElement(searchType,searchResult)) {
      let paragraphElement = searchResult.getElement();
      let name = paragraphElement.getHeading().name();
      if (name.includes("HEADING")) {
        let text = paragraphElement.getText();
        if (text != '')
          headers.push({text: text,type: name})
      }
    }
  
    // console.log({
    //   headers: headers,
    //   document_text: body.getText()
    // })
    return headers
  }
  