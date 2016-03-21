# Slider Field – Filtering Tests

- [Github Repo](https://github.com/twiro/slider)
- [Symphony Extensions](http://symphonyextensions.com/extensions/slider)


## Filter Modes

1. **is**
2. **less than**
4. **greater than**
6. **between**


## Sample Data – Single Values

1. `0`
2. `1`
3. `9`
4. `99`
5. `100`


## Tests – Single Values

###### Performed with Slider Field 1.3 and Symphony CMS 2.3.1, 2.4.0, 2.5.2 (DF only) and 2.6.2 (DF and PF)

| Nr. | Filter Mode | Filter Value | Expected Result | DF | PF |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1.1 | **is** | `0` | **1** | :white_check_mark: | :white_check_mark: |
| 1.2 | **is** | `1` | **2** | :white_check_mark: | :white_check_mark: |
| 1.3 | **is** | `99` | **4** | :white_check_mark: | :white_check_mark: |
| 1.4 | **is** | `99, 100` | **4**, **5** | :white_check_mark: | :white_check_mark: |
| 1.5 | **is** | `99 + 100` | – | :white_check_mark: | :x: |
| 2.1 | **less than** | `less than 0`  | – | :white_check_mark: | :white_check_mark: |
| 2.2 | **less than** | `less than 10`  | **1**, **2**, **3** | :white_check_mark: | :white_check_mark: |
| 2.3 | **less than** | `< 100` | **1**, **2**, **3**, **4** | :white_check_mark: | – |
| 3.1 | **greater than** | `greater than 0`  | **2**, **3**, **4**, **5** | :white_check_mark: | :white_check_mark: |
| 3.2 | **greater than** | `greater than 10`  | **4**, **5** | :white_check_mark: | :white_check_mark: |
| 3.3 | **greater than** | `> 100` | – | :white_check_mark: | – |
| 3.4 | **greater than** + **less than** | `> 1 + < 100` | **3**, **4** | :white_check_mark: | – |
| 4.1 | **between** | `0 to 1`  | **1**, **2** | :white_check_mark: | :white_check_mark: |
| 4.2 | **between** | `0 to 10`  | **1**, **2**, **3** | :white_check_mark: | :white_check_mark: |
| 4.3 | **between** | `0 to 100`  | **1**, **2**, **3**, **4**, **5** | :white_check_mark: | :white_check_mark: |
| 4.3 | **between** | `1-99`  | **2**, **3**, **4** | :white_check_mark: | :white_check_mark: |
| 4.4 | **between** | `0-1, 99-100`  | **1**, **2**, **4**, **5** | :white_check_mark: | :white_check_mark: |

<sup>
<strong>DF</strong> = Datasource Filtering
<i>/</i>
<strong>PF</strong> = Publish Filtering
</sup>


## Sample Data – Value Range

1. `0-1`
2. `1-10`
3. `9-90`
4. `50-100`
5. `100-100`


## Tests – Value Range

###### Performed with Slider Field 1.3 and Symphony CMS 2.3.1, 2.4.0, 2.5.2 (DF only) and 2.6.2 (DF and PF)

| Nr. | Filter Mode | Filter Value | Expected Result | DF | PF |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1.1 | **is** | `0` | **1** | :white_check_mark: | :white_check_mark: |
| 1.2 | **is** | `1` | **1**, **2** | :white_check_mark: | :white_check_mark: |
| 1.3 | **is** | `99` | **4** | :white_check_mark: | :white_check_mark: |
| 1.4 | **is** | `99, 100` | **4**, **5** | :white_check_mark: | :white_check_mark: |
| 1.5 | **is** | `99 + 100` | **4** | :white_check_mark: | :white_check_mark: |
| 2.1 | **less than** | `less than 0`  | – | :white_check_mark: | :white_check_mark: |
| 2.2 | **less than** | `less than 10`  | **1** | :white_check_mark: | :white_check_mark: |
| 2.3 | **less than** | `< 100` | **1**, **2**, **3** | :white_check_mark: | – |
| 3.1 | **greater than** | `greater than 0`  | **2**, **3**, **4**, **5** | :white_check_mark: | :white_check_mark: |
| 3.2 | **greater than** | `greater than 10`  | **4**, **5** | :white_check_mark: | :white_check_mark: |
| 3.3 | **greater than** | `> 100` | – | :white_check_mark: | –|
| 3.4 | **greater than** + **less than** | `> 1 + < 100` | **3** | :white_check_mark: | – |
| 4.1 | **between** | `0 to 1`  | **1** | :white_check_mark: | :white_check_mark: |
| 4.2 | **between** | `0 to 10`  | – | :white_check_mark: | :white_check_mark: |
| 4.3 | **between** | `9 to 10`  | **2**, **3** | :white_check_mark: | :white_check_mark: |
| 4.4 | **between** | `50-75`  | **3**, **4** | :white_check_mark: | :white_check_mark: |
| 4.4 | **between** | `0-100`  | – | :white_check_mark: | :white_check_mark: |
| 4.5 | **between** | `0-1, 50-75`  | **1**, **3**, **4** | :white_check_mark: | :white_check_mark: |
| 4.6 | **between** | `50-75 + 80-90`  | **3**, **4** | :white_check_mark: | :white_check_mark: |

<sup>
<strong>DF</strong> = Datasource Filtering
<i>/</i>
<strong>PF</strong> = Publish Filtering
</sup>