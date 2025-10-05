<?php $token = form_token_issue(); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบประเมินตนเอง CII</title>
    <style>
        /* Reset และ Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #000000;
            line-height: 1.6;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
        }
        
        h1 {
            text-align: center;
            color: #000000;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: bold;
        }
        
        /* Excel-style Tab System */
        .excel-tabs {
            display: flex;
            background-color: #f8f9fa;
            border-bottom: 3px solid #dee2e6;
            margin-bottom: 0;
            overflow-x: auto;
        }
        
        .excel-tab {
            padding: 15px 25px;
            background-color: #e9ecef;
            border: none;
            border-right: 1px solid #dee2e6;
            cursor: pointer;
            font-weight: 600;
            color: #000000;
            white-space: nowrap;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .excel-tab:hover {
            background-color: #f8f9fa;
        }
        
        .excel-tab.active {
            background-color: #ffffff;
            color: #000000;
            border-bottom: 3px solid #007bff;
            font-weight: 700;
        }
        
        .badge {
            background-color: #6c757d;
            color: #ffffff;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 8px;
        }
        
        /* Content Area */
        .excel-content {
            background-color: #ffffff;
            border: 2px solid #dee2e6;
            border-top: none;
        }
        
        .tab-panel {
            display: none;
            padding: 30px;
            background-color: #ffffff;
        }
        
        .tab-panel.active {
            display: block;
        }
        
        /* Legend */
        .legend-bar {
            background-color: #fff3cd;
            border: 2px solid #ffeaa7;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
        }
        
        .legend-title {
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .legend-items {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #000000;
            font-weight: 600;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #000000;
        }
        
        .legend-color.l1 { background-color: #dc3545; }
        .legend-color.l2 { background-color: #ffc107; }
        .legend-color.l3 { background-color: #28a745; }
        
        /* Category Summary */
        .cat-summary {
            background-color: #e7f3ff;
            border: 2px solid #007bff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .cat-summary h3 {
            color: #000000;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .cat-progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .cat-stats {
            color: #000000;
            font-weight: 600;
        }
        
        .progress-bar {
            flex: 1;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin: 0 15px;
            min-width: 200px;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.3s;
        }
        
        .progress-fill.level-เขียว { background-color: #28a745; }
        .progress-fill.level-เหลือง { background-color: #ffc107; }
        .progress-fill.level-แดง { background-color: #dc3545; }
        
        /* Questions */
        .q-item {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
        }
        
        .q-text {
            color: #000000;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .q-text strong {
            color: #007bff;
            font-weight: bold;
        }
        
        .weight {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 600;
            background-color: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 10px;
        }
        
        /* Score Options */
        .score-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        
        .score-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background-color: #ffffff;
            border: 3px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #000000;
            text-align: center;
            min-height: 80px;
        }
        
        .score-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .score-option.score-1 {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        .score-option.score-2 {
            border-color: #ffc107;
            background-color: #fffbf0;
        }
        
        .score-option.score-3 {
            border-color: #28a745;
            background-color: #f0fff4;
        }
        
        .score-option.active.score-1 {
            background-color: #dc3545;
            color: #ffffff;
            font-weight: bold;
        }
        
        .score-option.active.score-2 {
            background-color: #ffc107;
            color: #000000;
            font-weight: bold;
        }
        
        .score-option.active.score-3 {
            background-color: #28a745;
            color: #ffffff;
            font-weight: bold;
        }
        
        .score-option input {
            display: none;
        }
        
        .score-number {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .score-text {
            font-size: 0.9rem;
            line-height: 1.3;
        }
        
        /* Submit Button */
        .submit-section {
            background-color: #ffffff;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #dee2e6;
            margin-top: 30px;
        }
        
        .submit-btn {
            background-color: #28a745;
            color: #ffffff;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .score-grid {
                grid-template-columns: 1fr;
            }
            
            .excel-tabs {
                flex-direction: column;
            }
            
            .excel-tab {
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
            
            .cat-progress-info {
                flex-direction: column;
                align-items: stretch;
            }
            
            .progress-bar {
                margin: 10px 0;
            }
        }
        
        /* Success Feedback */
        .save-feedback {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 1000;
            font-weight: bold;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>แบบประเมินตนเอง CII (Cyber Infrastructure & Information)</h1>
        
        <form method="post" action="?a=save_answers" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
            <!-- Legend -->
            <div class="legend-bar">
                <div class="legend-title">เกณฑ์การให้คะแนน:</div>
                <div class="legend-items">
                    <div class="legend-item">
                        <div class="legend-color l1"></div>
                        <span>1 = ไม่มีหลักฐาน</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color l2"></div>
                        <span>2 = มีบางส่วน</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color l3"></div>
                        <span>3 = มีครบถ้วน</span>
                    </div>
                </div>
            </div>

            <!-- Tab System -->
            <div class="excel-tabs">
                <?php $first = true; foreach ($byCat as $cid => $cat): ?>
                    <button type="button" class="excel-tab <?= $first ? 'active' : '' ?>" onclick="switchTab(<?= (int)$cid ?>)">
                        <?= htmlspecialchars($cat['name']) ?>
                        <span class="badge"><?= count($cat['questions']) ?></span>
                    </button>
                <?php $first = false; endforeach; ?>
            </div>

            <div class="excel-content">
                <?php $first = true; foreach ($byCat as $cid => $cat): $sc = $scoreMap[$cid] ?? ['percent'=>0,'level'=>'สูง']; ?>
                    <div id="tab-<?= (int)$cid ?>" class="tab-panel <?= $first ? 'active' : '' ?>">
                        <!-- Category Summary -->
                        <div class="cat-summary">
                            <h3><?= htmlspecialchars($cat['name']) ?></h3>
                            <div class="cat-progress-info">
                                <span class="cat-stats">เอกสารแนบ: <?= (int)($cat['doc_count'] ?? 0) ?> ไฟล์</span>
                                <div class="progress-bar">
                                    <div class="progress-fill level-<?= htmlspecialchars($sc['level'] ?? 'สูง') ?>" style="width:<?= (int)($sc['percent'] ?? 0) ?>%"></div>
                                </div>
                                <span class="cat-stats"><?= (int)($sc['percent'] ?? 0) ?>% · <?= htmlspecialchars($sc['level'] ?? 'สูง') ?></span>
                                <a class="btn" href="?a=upload_doc&cid=<?= (int)$cid ?>" style="background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold;">แนบเอกสาร</a>
                            </div>
                        </div>

                        <!-- Questions -->
                        <?php foreach ($cat['questions'] as $q): $qid=(int)$q['id']; $sel=(int)($answers[$qid] ?? 0); ?>
                            <div class="q-item">
                                <div class="q-text">
                                    <strong><?= htmlspecialchars($q['code']) ?></strong>: <?= htmlspecialchars($q['text']) ?>
                                    <span class="weight">น้ำหนัก: <?= (int)$q['weight'] ?></span>
                                </div>
                                <div class="score-grid" data-qid="<?= $qid ?>">
                                    <label class="score-option score-1 <?= $sel===1?'active':'' ?>">
                                        <input type="radio" name="q<?= $qid ?>" value="1" <?= $sel===1?'checked':'' ?> />
                                        <div class="score-number">1</div>
                                        <div class="score-text">ไม่มีหลักฐาน</div>
                                    </label>
                                    <label class="score-option score-2 <?= $sel===2?'active':'' ?>">
                                        <input type="radio" name="q<?= $qid ?>" value="2" <?= $sel===2?'checked':'' ?> />
                                        <div class="score-number">2</div>
                                        <div class="score-text">มีบางส่วน</div>
                                    </label>
                                    <label class="score-option score-3 <?= $sel===3?'active':'' ?>">
                                        <input type="radio" name="q<?= $qid ?>" value="3" <?= $sel===3?'checked':'' ?> />
                                        <div class="score-number">3</div>
                                        <div class="score-text">มีครบถ้วน</div>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>

            <div class="submit-section">
                <input type="hidden" name="form_token" value="<?= htmlspecialchars($token) ?>" />
                <button class="submit-btn" type="submit">บันทึกคำตอบและดูผล</button>
            </div>
        </form>
    </div>

    <script>
        // Tab switching
        function switchTab(categoryId) {
            document.querySelectorAll('.excel-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById('tab-' + categoryId).classList.add('active');
        }

        // Score selection
        document.querySelectorAll('.score-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                
                const scoreGrid = this.parentElement;
                const qid = scoreGrid.dataset.qid;
                const input = this.querySelector('input');
                const value = input.value;
                
                scoreGrid.querySelectorAll('.score-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                input.checked = true;
                
                // Auto-save
                fetch('?a=save_answer', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `qid=${qid}&val=${value}`
                }).then(() => {
                    showSaveFeedback();
                }).catch(() => {
                    console.log('Save failed, will retry on form submit');
                });
            });
        });

        function showSaveFeedback() {
            const feedback = document.createElement('div');
            feedback.textContent = '✓ บันทึกแล้ว';
            feedback.className = 'save-feedback';
            document.body.appendChild(feedback);
            setTimeout(() => feedback.remove(), 2000);
        }
    </script>
</body>
</html>