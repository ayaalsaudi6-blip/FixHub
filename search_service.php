<?php

require_once "config/Database.php";
require_once "classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';

$results = $service->search($keyword, $category, $location);
$categories = $service->getCategories();
$locations = $service->getLocations();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search Services</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ddd;
            margin-bottom: 30px;
        }

        .search-form input,
        .search-form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .search-form input[type="text"] {
            flex: 1;
            min-width: 200px;
        }

        .search-form button {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            background: #f97316;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        .search-form button:hover {
            background: #ea580c;
        }

        .results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ddd;
        }

        .service-card h3 {
            margin-top: 0;
        }

        .service-card .location {
            display: block;
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .service-card a {
            display: inline-block;
            margin-top: 10px;
            color: #f97316;
            text-decoration: none;
            font-weight: bold;
        }

        .no-results {
            color: #888;
        }

    </style>

</head>

<body>

    <div class="container">

        <h1>Search Services</h1>

        <form id="searchForm" class="search-form">

            <input
                type="text"
                name="keyword"
                placeholder="Search for a service..."
                value="<?php echo htmlspecialchars($keyword); ?>"
            >

            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat) { ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php } ?>
            </select>

            <select name="location">
                <option value="">All Locations</option>
                <?php foreach ($locations as $loc) { ?>
                    <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo $location === $loc ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($loc); ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Search</button>

        </form>

        <div class="results" id="resultsBox">

            <?php if (empty($results)) { ?>

                <p class="no-results">No services found.</p>

            <?php } else { ?>

                <?php foreach ($results as $item) { ?>

                    <div class="service-card">

                        <h3><?php echo htmlspecialchars($item['icon'] . ' ' . $item['name']); ?></h3>

                        <?php if (!empty($item['location'])) { ?>
                            <span class="location">📍 <?php echo htmlspecialchars($item['location']); ?></span>
                        <?php } ?>

                        <p><?php echo htmlspecialchars($item['description']); ?></p>

                        <a href="service_details.php?id=<?php echo $item['id']; ?>">View Details</a>

                    </div>

                <?php } ?>

            <?php } ?>
        </div>
    </div>

    <script>

    const searchForm = document.getElementById('searchForm');
    const resultsBox = document.getElementById('resultsBox');
    const keywordInput = searchForm.querySelector('[name="keyword"]');
    const categorySelect = searchForm.querySelector('[name="category"]');
    const locationSelect = searchForm.querySelector('[name="location"]');

    let debounceTimer;

    function runSearch() {

        const keyword = keywordInput.value;
        const category = categorySelect.value;
        const location = locationSelect.value;

        const url = `ajax_search.php?keyword=${encodeURIComponent(keyword)}&category=${encodeURIComponent(category)}&location=${encodeURIComponent(location)}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {

                if (data.length === 0) {
                    resultsBox.innerHTML = '<p class="no-results">No services found.</p>';
                    return;
                }

                resultsBox.innerHTML = data.map(item => `
                    <div class="service-card">
                        <h3>${item.icon ?? ''} ${item.name}</h3>
                        ${item.location ? `<span class="location">📍 ${item.location}</span>` : ''}
                        <p>${item.description}</p>
                        <a href="service_details.php?id=${item.id}">View Details</a>
                    </div>
                `).join('');

            });
    }

    keywordInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runSearch, 300);
    });

    categorySelect.addEventListener('change', runSearch);
    locationSelect.addEventListener('change', runSearch);

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        runSearch();
    });

    </script>

</body>
</html>