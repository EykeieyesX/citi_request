<?php
// Fetch Data from API
function getBirthData() {
    $apiUrl = "https://civilregistrar.lgu2.com/api/birth.php";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die("Error: Unable to fetch data from API");
    }

    return json_decode($response, true);
}

$birthRecords = getBirthData();
?>

<div class="birth-container" style="max-width: 100%; padding: 20px; margin: 20px 0; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h1 class="birth-title" style="font-size: 2rem; font-weight: bold; text-align: center; color: #1D4ED8; margin-bottom: 20px;">
        Birth Certificate Records
    </h1>

    <?php if (empty($birthRecords)): ?>
        <p class="no-records" style="text-align: center; color: #DC2626; font-weight: bold;">
            No records found.
        </p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="birth-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">ID</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Child Name</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Sex</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Birth Date</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Father</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Mother</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($birthRecords as $record): ?>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['id']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['child_first_name'] . ' ' . $record['child_middle_name'] . ' ' . $record['child_last_name']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['child_sex']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?php 
                                    $childDOB = new DateTime($record['child_date_of_birth']);
                                    echo $childDOB->format('F d, Y'); 
                                ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['father_first_name'] . ' ' . $record['father_last_name']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['mother_first_name'] . ' ' . $record['mother_last_name']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center; font-weight: bold; <?= ($record['status'] === 'completed') ? 'color: #16A34A;' : 'color: #DC2626;'; ?>">
                                <?= htmlspecialchars($record['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>