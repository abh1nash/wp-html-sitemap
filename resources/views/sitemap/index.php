
<?php foreach($data as $category): ?>
    <?php if ($category["url"]): ?>
    <a href="<?php echo $category["url"]; ?>" class="abhinash-sitemap-category-link">
    <?php endif; ?>
    <h2 class="abhinash-sitemap-category-heading">
        <?php echo $category['name']; ?>
    </h2>
    <?php if ($category["url"]): ?>
    </a>
    <?php endif; ?>
    <ul class="abhinash-sitemap-category-page-list">
        <?php foreach($category['pages'] as $page): ?>
            <li class="abhinash-sitemap-list-item page-id-<?php echo $page->ID; ?>">
                <a href="<?php echo get_the_permalink($page->ID); ?>" class="abhinash-sitemap-list-link">
                    <?php echo $page->post_title; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>