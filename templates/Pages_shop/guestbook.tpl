     <h1>Гостевая книга</h1>

	{{GUESTBOOKFEED}}      
		
		<form  id="order" action="add/" method="POST" >
            <p><strong>Оставить отзыв о сайте:</strong></p>
            <p>Введите ФИО: <input type="text" name="name" maxlength="30" required></p>
            <p>Введите Email: <input type="email" name="email" maxlength="20" required></p>
            <p>Введите Текст:<br> <textarea name="text" rows="10" required></textarea></p>
            <p><input class='add-to-basket' type="submit" name="submit"></p>
        </form>	