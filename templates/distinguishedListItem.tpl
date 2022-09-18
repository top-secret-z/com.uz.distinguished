{if USER_DISTINGUISHED_DISPLAY == 'right'}
    <li data-object-id="{@$user->userID}">
        <div class="box48">
            <a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{@$user->getAvatar()->getImageTag(48)}</a>

            <div class="details userInformation">
                {include file='distinguishedUserInformation'}
            </div>
        </div>
    </li>
{else}
    <li data-object-id="{@$user->userID}">
        <div class="distLeft">
            {#$user->$sortField}
        </div>

        <div class="box48">
            <a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{@$user->getAvatar()->getImageTag(48)}</a>

            <div class="details userInformation">
                {include file='userInformation'}
            </div>
        </div>
    </li>
{/if}
