{% import "position\\navi\\dropdown\\_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<ul>
{% for node in naviDoc.naviIndex %}
	{{ item.loop(node) }}
{% endfor %}
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}