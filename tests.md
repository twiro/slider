# Slider Field

- [Github Repo](https://github.com/twiro/slider)
- [Symphony Extensions](http://symphonyextensions.com/extensions/slider)


## Filter Modes

1. **is**
2. **smaller than**
4. **greater than**
6. **between**


## Sample Data – Single Values

1. `0`
2. `1`
3. `9`
4. `99`
5. `100`


## Tests – Single Values

###### Performed with Symphony CMS 2.3.1 and Slider Field 0.2

| Nr. | Filter Mode | Filter Value | Expected Result | DF | PF |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1.1 | **is** | `0` | **1** | :white_check_mark: | … |
| 1.2 | **is** | `1` | **2** | :white_check_mark: | … |
| 1.3 | **is** | `99` | **4** | :white_check_mark: | … |
| 1.4 | **is** | `99, 100` | **4**, **5** | :white_check_mark: | … |
| 1.5 | **is** | `99 + 100` | – | :white_check_mark: | … |
| 2.1 | **smaller than** | `smaller than 0`  | – | :x: | … |
| 2.2 | **smaller than** | `smaller than 10`  | **1**, **2**, **3** | :x: | … |
| 2.3 | **smaller than** | `< 100` | **1**, **2**, **3**, **4** | :x: | … |
| 3.1 | **greater than** | `greater than 0`  | **2**, **3**, **4**, **5** | :x: | … |
| 3.2 | **greater than** | `greater than 10`  | **4**, **5** | :x: | … |
| 3.3 | **greater than** | `> 100` | – | :x: | … |
| 4.1 | **between** | `0 to 1`  | **1**, **2** | :x: | … |
| 4.2 | **between** | `0 to 10`  | **1**, **2**, **3** | :x: | … |
| 4.3 | **between** | `0 to 100`  | **1**, **2**, **3**, **4**, **5** | :x: | … |
| 4.3 | **between** | `1-99`  | **1**, **2**, **3** | :x: | … |

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

###### Performed with Symphony CMS 2.3.1 and Slider Field 0.2

| Nr. | Filter Mode | Filter Value | Expected Result | DF | PF |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1.1 | **is** | `0` | **1** | :white_check_mark: | … |
| 1.2 | **is** | `1` | **1**, **2** | :white_check_mark: | … |
| 1.3 | **is** | `99` | **4** | :x: | … |
| 1.4 | **is** | `99, 100` | **4**, **5** | :x: | … |
| 1.5 | **is** | `99 + 100` | **4** | :x: | … |
| 2.1 | **smaller than** | `smaller than 0`  | – | :x: | … |
| 2.2 | **smaller than** | `smaller than 10`  | **1** | :x: | … |
| 2.3 | **smaller than** | `< 100` | **1**, **2**, **3** | :x: | … |
| 3.1 | **greater than** | `greater than 0`  | **2**, **3**, **4**, **5** | :x: | … |
| 3.2 | **greater than** | `greater than 10`  | **4**, **5** | :x: | … |
| 3.3 | **greater than** | `> 100` | – | :x: | … |
| 4.1 | **between** | `0 to 1`  | **1** | :x: | … |
| 4.2 | **between** | `0 to 10`  | – | :x: | … |
| 4.3 | **between** | `9 to 10`  | **2**, **3** | :x: | … |
| 4.4 | **between** | `50-75`  | **3**, **4** | :x: | … |
| 4.4 | **between** | `0-100`  | – | :x: | … |

<sup>
<strong>DF</strong> = Datasource Filtering
<i>/</i>
<strong>PF</strong> = Publish Filtering
</sup>