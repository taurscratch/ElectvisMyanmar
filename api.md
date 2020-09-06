### api step by step
### created at 5th Sept 2020
### 1. country level apis

## api 1.1
## '/api/v1/nationalityhouse_total/{year}
## To be used in donut chart for distribution
## values for year: 2010,2015

## api 1.2
## '/api/v1/nationalityhouse_country/{year}
## To be used in country map to color each region
## values for year: 2010,2015

## api 1.3
## '/api/v1/nationalityhouse_compare/country
## To be used in bar graph to compare each party between years

## api 1.4
## '/api/v1/census/country
## To be used in donut graph to view the share between present vote and absent votes

## api 1.5 (added 6th Sept 2020)
## '/api/v1/nationalityhouse_total/2015?type=gender
## To be used in donut graph for gender distribution

### 2. region level apis

## api 2.1
## '/api/v1/nationalityhouse_region/{region}/{year}
## To be used in region map to color each constitution
## values for region: value of region_state key from api 1.2, year: 2010,2015

## api 2.2
## '/api/v1/nationalityhouse_region/{region}/{year}?type=total
## To be used in donut chart for distribution

## api 2.3
## '/api/v1/nationalityhouse_compare/region?region={region}
## To be used in bar graph to compare each party between years
## values for region: value of region_state key from api 1.2

## api 2.4 (added 6th Sept 2020)
## '/api/v1/nationalityhouse_candidates/{region}/{year}
## To be used in donut chart for two differnt distributions between elected gender and total gender

## api 2.5 (added 6th Sept 2020)
## '/api/v1/census/region?area_id={area_id}&region_id={region_id}&year=2015
## To be used in donut chart for vote/no vote distribution
## values for region_id: value of region_state key from api 1.2
## values for area_id: value of area_id key from api 1.2

### 3. consitution level apis

## api 3.1
## '/api/v1/nationalityhouse_seat/{seat}
## To be used in line graph for voting results of the seat

## api 3.2
## '/api/v1/census/constitution?house_id={house_id}
## To be used in donut graph for vote/no vote distribution
## value for house_id: value of nationality_house_id key from api 2.1



