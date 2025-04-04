<?php
// Fetch Data from API
function getMarriageData() {
    $apiUrl = "https://civilregistrar.lgu2.com/api/marriage.php";

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

$marriageRecords = getMarriageData();
?>

<div class="marriage-container" style="max-width: 100%; padding: 20px; margin: 20px 0; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h1 class="marriage-title" style="font-size: 2rem; font-weight: bold; text-align: center; color: #1D4ED8; margin-bottom: 20px;">
        Marriage Certificate Records
    </h1>

    <?php if (empty($marriageRecords)): ?>
        <p class="no-records" style="text-align: center; color: #DC2626; font-weight: bold;">
            No records found.
        </p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="marriage-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">ID</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Groom</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Bride</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Marriage Date</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Marriage Place</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Groom Witness</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Bride Witness</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marriageRecords as $record): ?>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['id']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['groom_first_name'] . ' ' . $record['groom_middle_name'] . ' ' . $record['groom_last_name'] . ' ' . $record['groom_suffix']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['bride_first_name'] . ' ' . $record['bride_middle_name'] . ' ' . $record['bride_last_name'] . ' ' . $record['bride_suffix']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?php 
                                    $marriageDate = new DateTime($record['marriage_date']);
                                    echo $marriageDate->format('F d, Y'); 
                                ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['marriage_place']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['groom_witness']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['bride_witness']); ?>
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
